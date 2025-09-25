<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    /**
     * Crée une session Stripe Checkout et redirige l'utilisateur.
     */
    public function create()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with('items.book')
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $secret = config('services.stripe.secret');
        $currency = config('services.stripe.currency', 'CAD');
        if (!$secret) {
            Log::error('Stripe secret manquant dans config(services.stripe.secret)');
            return redirect()->route('cart.index')->with('error', 'Configuration Stripe manquante.');
        }
        Stripe::setApiKey($secret);

        // Construire les line_items Stripe depuis le panier
        $lineItems = [];
        foreach ($cart->items as $item) {
            $unit = (float) ($item->price ?? 0);
            $qty  = (int) $item->quantity;
            if ($qty < 1 || $unit <= 0) {
                continue;
            }

            $lineItems[] = [
                'price_data' => [
                    'currency' => strtolower($currency),
                    'product_data' => [
                        'name' => $item->book->title,
                    ],
                    'unit_amount' => (int) round($unit * 100), // cents
                ],
                'quantity' => $qty,
            ];
        }

        if (empty($lineItems)) {
            return redirect()->route('cart.index')->with('error', 'Articles invalides dans le panier.');
        }

        try {
            $session = StripeSession::create([
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('checkout.cancel'),
            ]);
            return redirect()->away($session->url);
        } catch (\Throwable $e) {
            Log::error('Stripe checkout create failed', ['error' => $e->getMessage()]);
            return redirect()->route('cart.index')->with('error', 'Erreur lors de la création du paiement Stripe.');
        }
    }

    /**
     * Retour succès: vérifie la session Stripe, crée la commande, vide le panier.
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('cart.index')->with('error', 'Session de paiement introuvable.');
        }

        $secret = config('services.stripe.secret');
        if (!$secret) {
            Log::error('Stripe secret manquant dans success()');
            return redirect()->route('cart.index')->with('error', 'Configuration Stripe manquante.');
        }
        Stripe::setApiKey($secret);

        try {
            $session = StripeSession::retrieve($sessionId);
        } catch (\Throwable $e) {
            Log::error('Stripe session retrieve failed', ['session_id' => $sessionId, 'error' => $e->getMessage()]);
            return redirect()->route('cart.index')->with('error', 'Impossible de vérifier le paiement.');
        }

        if (($session->payment_status ?? null) !== 'paid') {
            return redirect()->route('cart.index')->with('error', 'Paiement non confirmé.');
        }

        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)
            ->with('items.book')
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return redirect()->route('books.index')->with('error', 'Panier vide.');
        }

        // Totaux
        $subtotal = 0.0;
        foreach ($cart->items as $it) {
            $price = (float) ($it->price ?? 0);
            $qty   = (int) $it->quantity;
            $subtotal += $price * $qty;
        }
        $discount = 0.0;
        $gst = 0.0;
        $qst = 0.0;
        $shipping = 0.0;
        $total = $subtotal - $discount + $gst + $qst + $shipping;
        $currency = strtoupper(config('services.stripe.currency', 'CAD'));

        try {
            DB::transaction(function () use ($cart, $userId, $subtotal, $discount, $gst, $qst, $shipping, $total, $currency, $session) {
                // Créer la commande en respectant les colonnes de ta migration
                $order = new Order();
                $order->user_id                 = $userId;
                $order->currency                = $currency;
                $order->subtotal                = $subtotal;
                $order->discount                = $discount;
                $order->gst                     = $gst;
                $order->qst                     = $qst;
                $order->shipping                = $shipping;
                $order->total                   = $total;
                $order->provider                = 'stripe';
                $order->provider_session_id     = $session->id;
                $order->provider_payment_intent = $session->payment_intent ?? null;
                $order->status                  = 'paid';
                $order->meta                    = [
                    'customer_email' => $session->customer_details->email ?? null,
                ];
                $order->save();

                // Créer les lignes de commande (order_items) - migration exige title, author, quantity, unit_price, line_total
                foreach ($cart->items as $ci) {
                    $unit = (float) ($ci->price ?? 0);
                    $qty  = (int) $ci->quantity;
                    $line = $unit * $qty;

                    $oi = new OrderItem();
                    $oi->order_id   = $order->id;
                    $oi->book_id    = $ci->book_id;
                    $oi->title      = $ci->book->title ?? 'Livre';
                    $oi->author     = $ci->book->author ?? null;
                    $oi->quantity   = $qty;
                    $oi->unit_price = $unit;
                    $oi->line_total = $line;
                    $oi->save();
                }

                // Vider le panier
                $cart->items()->delete();
            });
        } catch (\Throwable $e) {
            Log::error('Order creation failed after Stripe payment', [
                'user_id'    => $userId,
                'session_id' => $sessionId,
                'error'      => $e->getMessage(),
            ]);
            return redirect()->route('cart.index')->with('error', 'Paiement confirmé, mais la création de la commande a échoué.');
        }

        return redirect()->route('books.index')
            ->with('success', 'Merci! Paiement confirmé et commande créée.');
    }

    public function cancel()
    {
        return redirect()->route('cart.index')->with('error', 'Paiement annulé.');
    }
}
