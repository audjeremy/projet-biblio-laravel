<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with('items','user')->latest()->paginate(20);

        // On réutilise la même vue que côté user
        return view('orders.index', [
            'orders'  => $orders,
            'isAdmin' => true, // flag si tu veux afficher des colonnes en plus
        ]);
    }

    public function show(Order $order): View
    {
        $order->load('items','user');

        // On réutilise la même vue que côté user
        return view('orders.show', [
            'order'   => $order,
            'isAdmin' => true,
        ]);
    }
}