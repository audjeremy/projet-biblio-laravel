<?php

namespace App\Support;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderCreator
{
    /**
     * Crée une commande (orders + order_items) depuis le panier DB de l'utilisateur.
     * Vide le panier si tout va bien.
     *
     * @param  Cart   $cart
     * @param  string $gateway   'stripe'|'paypal'
     * @param  array  $meta      infos de la passerelle (ids, tokens)
     */
    public static function fromCart(Cart $cart, string $gateway, array $meta = []): ?Order
    {
        $cart->load('items.book');

        if ($cart->items->isEmpty()) {
            return null;
        }

        // Recalcule les totaux avec la même logique que l'écran (cohérence)
        $controller = app(\App\Http\Controllers\CartController::class);
        $totals = (new \ReflectionClass($controller))
            ->getMethod('computeTotals')
            ->invoke($controller, $cart);

        return DB::transaction(function () use ($cart, $gateway, $totals, $meta) {
            $order = Order::create([
                'user_id'  => $cart->user_id,
                'status'   => 'paid',
                'gateway'  => $gateway,
                'currency' => $totals['currency'] ?? '$',
                'subtotal' => $totals['subtotal'] ?? 0,
                'discount' => $totals['discount'] ?? 0,
                'tax'      => (($totals['gst'] ?? 0) + ($totals['qst'] ?? 0)),
                'total'    => $totals['total'] ?? 0,
                'meta'     => $meta,
            ]);

            foreach ($cart->items as $it) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id'  => $it->book_id,
                    'title'    => $it->book->title ?? 'Livre',
                    'price'    => $it->price,
                    'quantity' => $it->quantity,
                ]);
            }

            // Vider le panier
            $cart->items()->delete();
            $cart->update(['total' => 0]);

            return $order;
        });
    }
}