<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

// PayPal SDK
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

class PaypalController extends Controller
{
    private function client(): PayPalHttpClient
    {
        $clientId = config('services.paypal.client_id');
        $secret   = config('services.paypal.client_secret');
        $mode     = config('services.paypal.mode', 'sandbox');

        $env = $mode === 'live'
            ? new ProductionEnvironment($clientId, $secret)
            : new SandboxEnvironment($clientId, $secret);

        return new PayPalHttpClient($env);
    }

    public function create()
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->with('items.book')->firstOrFail();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $totals   = $this->computeTotalsFromCart($cart);
        $currency = strtoupper(config('services.paypal.currency', 'CAD'));

        // Items (nom + montant unitaire + quantité)
        $items = [];
        foreach ($cart->items as $it) {
            $items[] = [
                'name'        => $it->book->title,
                'unit_amount' => [
                    'currency_code' => $currency,
                    'value'         => number_format($it->price, 2, '.', ''),
                ],
                'quantity'    => (string) $it->quantity,
            ];
        }

        // PayPal veut un "breakdown" détaillé
        $amountBreakdown = [
            'item_total' => [
                'currency_code' => $currency,
                'value'         => number_format($cart->items->sum(fn($i) => $i->quantity * $i->price), 2, '.', ''),
            ],
        ];

        if ($totals['discount'] > 0) {
            $amountBreakdown['discount'] = [
                'currency_code' => $currency,
                'value'         => number_format($totals['discount'], 2, '.', ''),
            ];
        }
        $taxTotal = $totals['gst'] + $totals['qst'];
        if ($taxTotal > 0) {
            $amountBreakdown['tax_total'] = [
                'currency_code' => $currency,
                'value'         => number_format($taxTotal, 2, '.', ''),
            ];
        }
        if (($totals['shipping'] ?? 0) > 0) {
            $amountBreakdown['shipping'] = [
                'currency_code' => $currency,
                'value'         => number_format($totals['shipping'], 2, '.', ''),
            ];
        }

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'items' => $items,
                'amount' => [
                    'currency_code' => $currency,
                    'value'         => number_format($totals['total'], 2, '.', ''),
                    'breakdown'     => $amountBreakdown,
                ],
            ]],
            'application_context' => [
                'brand_name'  => config('app.name'),
                'landing_page'=> 'NO_PREFERENCE',
                'user_action' => 'PAY_NOW',
                'return_url'  => route('paypal.return'),
                'cancel_url'  => route('paypal.cancel'),
            ],
        ];

        $client   = $this->client();
        $response = $client->execute($request);

        // Récupérer le lien d’approbation
        foreach ($response->result->links as $link) {
            if ($link->rel === 'approve') {
                return redirect($link->href);
            }
        }

        return redirect()->route('cart.index')->with('error', 'Impossible de créer la commande PayPal.');
    }

    public function approveReturn(Request $request)
    {
        $token = $request->query('token'); // id de l'order PayPal
        if (!$token) {
            return redirect()->route('cart.index')->with('error', 'Retour PayPal invalide.');
        }

        $client = $this->client();

        // Capture du paiement
        $capture = new OrdersCaptureRequest($token);
        $capture->prefer('return=representation');

        $result = $client->execute($capture);

        if (!isset($result->result->status) || $result->result->status !== 'COMPLETED') {
            return redirect()->route('cart.index')->with('error', 'Paiement PayPal non confirmé.');
        }

        // Construire la commande locale
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->with('items.book')->firstOrFail();
        if ($cart->items->isEmpty()) {
            return redirect()->route('books.index')->with('error', 'Panier vide.');
        }

        $totals   = $this->computeTotalsFromCart($cart);
        $currency = strtoupper(config('services.paypal.currency', 'CAD'));

        $order = Order::create([
            'user_id'                 => $userId,
            'currency'                => $currency,
            'subtotal'                => $totals['subtotal'],
            'discount'                => $totals['discount'],
            'gst'                     => $totals['gst'],
            'qst'                     => $totals['qst'],
            'shipping'                => $totals['shipping'],
            'total'                   => $totals['total'],
            'provider'                => 'paypal',
            'provider_session_id'     => $token,                         // PayPal Order ID
            'provider_payment_intent' => $this->extractCaptureId($result), // capture id
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

        // Nettoyer le panier
        $cart->items()->delete();
        $cart->update(['total' => 0]);
        session()->forget('coupon');

        return view('checkout.success', ['order' => $order]);
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }

    private function extractCaptureId($captureResponse): ?string
    {
        // Va chercher le premier capture id (si présent)
        try {
            $purchaseUnits = $captureResponse->result->purchase_units ?? [];
            foreach ($purchaseUnits as $pu) {
                $payments = $pu->payments->captures ?? [];
                foreach ($payments as $cap) {
                    if (!empty($cap->id)) {
                        return $cap->id;
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }
        return null;
    }

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
