<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cart->load('items.book');
        $totals = $this->computeTotals($cart);

        return view('cart.index', [
        'cart'    => $cart,
        'totals'  => $totals,
        ]);
    }

    public function add(Request $request, Book $book)
{
    $qty = max(1, (int) $request->input('quantity', 1));

    $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
    $item = $cart->items()->where('book_id', $book->id)->first();

    $unitPrice = $book->is_on_sale ? $book->discounted_price : (float) $book->price; // ← ICI

    if ($item) {
        // On n’écrase pas le prix historique de la ligne; on incrémente la qty.
        $item->increment('quantity', $qty);
    } else {
        $cart->items()->create([
            'book_id'  => $book->id,
            'quantity' => $qty,
            'price'    => $unitPrice, // ← ICI
        ]);
    }

    $cart->load('items');
    $this->updateTotal($cart);

    return redirect()->route('cart.index')->with('success', 'Livre ajouté au panier.');
}

    public function remove(CartItem $item)
    {
        $cart = $item->cart;
        $item->delete();
        $this->updateTotal($cart);

        return redirect()->route('cart.index')->with('success', 'Livre retiré du panier.');
    }

    public function updateQuantity(Request $request, CartItem $item)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $item->update(['quantity' => $request->quantity]);

        $this->updateTotal($item->cart);

        return redirect()->route('cart.index')->with('success', 'Quantité mise à jour.');
    }

    private function updateTotal(Cart $cart)
    {
        $total = $cart->items->sum(fn($i) => $i->quantity * $i->price);
        $cart->update(['total' => $total]);
    }

    /** -------------------------------
     *  Coupons (ex: PROMO10 = -10%)
     *  ------------------------------*/
    public function applyCoupon(Request $request)
    {
        $data = $request->validate(['code' => 'required|string|max:32']);
        $code = strtoupper(trim($data['code']));

        // Démo simple : deux codes exemples
        $catalog = [
            'PROMO10'   => ['type' => 'percent', 'value' => 0.10, 'label' => '-10%'],
            'STUDENT5'  => ['type' => 'percent', 'value' => 0.05, 'label' => '-5% étudiant'],
        ];

        if (!array_key_exists($code, $catalog)) {
            return back()->with('error', 'Code promo invalide.');
        }

        session(['coupon' => array_merge(['code' => $code], $catalog[$code])]);

        return redirect()->route('cart.index')->with('success', "Code promo « $code » appliqué.");
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return redirect()->route('cart.index')->with('success', 'Code promo retiré.');
    }
    // Calcul des totaux avec taxes, remise, frais de port
    private function computeTotals(Cart $cart): array
    {
        $currency = config('cart.currency', '$');
        $taxes    = config('cart.taxes', ['gst' => 0, 'qst' => 0]);
        $gstRate  = Arr::get($taxes, 'gst', 0);
        $qstRate  = Arr::get($taxes, 'qst', 0);

        $subtotal = (float) $cart->items->sum(fn($i) => $i->quantity * $i->price);

        // Remise
        $coupon   = session('coupon'); // ['code','type','value','label']
        $discount = 0.0;
        if ($coupon && $subtotal > 0) {
            if ($coupon['type'] === 'percent') {
                $discount = round($subtotal * (float)$coupon['value'], 2);
            } else if ($coupon['type'] === 'fixed') {
                $discount = min(round((float)$coupon['value'], 2), $subtotal);
            }
        }

        $base     = max(0, $subtotal - $discount);
        $gst      = round($base * $gstRate, 2);
        $qst      = round($base * $qstRate, 2);
        $shipping = (float) config('cart.shipping_flat', 0);
        $total    = round($base + $gst + $qst + $shipping, 2);

        return compact('currency', 'subtotal', 'discount', 'gst', 'qst', 'shipping', 'total', 'coupon');
    }
}
