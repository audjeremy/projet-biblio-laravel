<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Message;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // Si pas admin : rediriger gentiment vers ses commandes (option choisi)
        if (!$user || $user->role !== 'admin') {
            // On peut soit rediriger, soit afficher un mini tableau de bord user.
            // Ici: mini vue user = ses 5 dernières commandes
            $orders = Order::where('user_id', $user->id)->latest()->paginate(10);
            return view('dashboard', [
                'mode' => 'user',
                'orders' => $orders,
            ]);
        }

        // Admin: KPIs + tableaux
        $totalSales   = (float) Order::sum('total');
        $ordersCount  = (int)   Order::count();
        $unreadCount  = (int)   Message::where('is_read', false)->count();
        $newBooks10d  = (int)   Book::where('created_at', '>=', now()->subDays(10))->count();

        $latestOrders   = Order::with('user')->latest()->limit(5)->get();
        $latestMessages = Message::latest()->limit(5)->get();
        $latestBooks    = Book::latest()->limit(5)->get();

        return view('dashboard', [
            'mode'          => 'admin',
            'totalSales'    => $totalSales,
            'ordersCount'   => $ordersCount,
            'unreadCount'   => $unreadCount,
            'newBooks10d'   => $newBooks10d,
            'latestOrders'  => $latestOrders,
            'latestMessages'=> $latestMessages,
            'latestBooks'   => $latestBooks,
        ]);
    }
}