<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    /** Mes commandes (utilisateur courant) */
    public function index(): View
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('orders.index', compact('orders'));
    }

    /** Détail d’une commande (autorisé si owner ou admin) */
    public function show(Order $order): View
    {
        $this->authorize('view', $order); // Policy conseillée (sinon check simple)
        $order->load('items');            // assume relation items()

        return view('orders.show', compact('order'));
    }
}