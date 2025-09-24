<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

// Stripe SDK
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

        Stripe::setApiKey(config('stripe.secret'));

        // Lignes d'articles pour Stripe
        $lineItems = [];
        foreach ($cart->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => config('stripe.currency', 'cad'),
                    'unit_amount'  => (int) round($item->price * 100), // cents
                    'product_data' => [
                        'name' => $item->book->title,
                    ],
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Important: inclure {CHECKOUT_SESSION_ID} dans success_url
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'success_url'          => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => route('checkout.cancel'),
        ]);

        return redirect()->away($session->url);
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

        Stripe::setApiKey(config('stripe.secret'));
        $session = StripeSession::retrieve($sessionId);

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

        // Totaux (même logique que /cart)
        $totals = $this->computeTotalsFromCart($cart);

        // Créer la commande
        $order = Order::create([
            'user_id'                 => $userId,
            'currency'                => strtoupper(config('stripe.currency', 'cad')),
            'subtotal'                => $totals['subtotal'],
            'discount'                => $totals['discount'],
            'gst'                     => $totals['gst'],
            'qst'                     => $totals['qst'],
            'shipping'                => $totals['shipping'],
            'total'                   => $totals['total'],
            'provider'                => 'stripe',
            'provider_session_id'     => $sessionId,
            'provider_payment_intent' => $session->payment_intent ?? null,
            'status'                  => 'paid',
            'meta'                    => [
                'coupon' => session('coupon'),
            ],
        ]);

        foreach ($cart->items as $it) {
            OrderItem::create([
                'order_id'   => $order->id,
                'book_id'    => $it->book_id,
                'title'      => $it->book->title,
                'author'     => $it->book->author,
                'quantity'   => $it->quantity,
                'unit_price' => $it->price,
                'line_total' => round($it->quantity * $it->price, 2),
            ]);
        }

        // Vider le panier et nettoyer la session
        $cart->items()->delete();
        $cart->update(['total' => 0]);
        session()->forget('coupon');

        return view('checkout.success', ['order' => $order]);
    }

    /**
     * Retour annulation.
     */
    public function cancel()
    {
        return view('checkout.cancel');
    }

    /**
     * Recalcule les totaux à partir du panier (TPS/TVQ & remise).
     */
    private function computeTotalsFromCart(Cart $cart): array
    {
        $currency = config('cart.currency', '$');
        $taxes    = config('cart.taxes', ['gst' => 0, 'qst' => 0]);
        $gstRate  = Arr::get($taxes, 'gst', 0);
        $qstRate  = Arr::get($taxes, 'qst', 0);

        $subtotal = (float) $cart->items->sum(fn($i) => $i->quantity * $i->price);
        $coupon   = session('coupon'); // ['code','type','value','label']
        $discount = 0.0;

        if ($coupon && $subtotal > 0) {
            if (($coupon['type'] ?? null) === 'percent') {
                $discount = round($subtotal * (float)$coupon['value'], 2);
            } elseif (($coupon['type'] ?? null) === 'fixed') {
                $discount = min(round((float)$coupon['value'], 2), $subtotal);
            }
        }

        $base     = max(0, $subtotal - $discount);
        $gst      = round($base * $gstRate, 2);
        $qst      = round($base * $qstRate, 2);
        $shipping = (float) config('cart.shipping_flat', 0);
        $total    = round($base + $gst + $qst + $shipping, 2);

        return compact('currency','subtotal','discount','gst','qst','shipping','total','coupon');
    }
}
