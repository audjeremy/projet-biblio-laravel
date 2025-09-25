<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\OrderController as UserOrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\MessageController;

// Accueil = /books
Route::redirect('/', '/books')->name('home');

/*
|--------------------------------------------------------------------------
| Dashboard (ADMIN seulement) : liste toutes les commandes
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth','verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Compte (auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Contact (public)
|--------------------------------------------------------------------------
*/
Route::get('/contact',  [MessageController::class, 'create'])->name('messages.create');
Route::post('/contact', [MessageController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('messages.store');

/*
|--------------------------------------------------------------------------
| Panier & Checkout (auth + verified)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Panier
    Route::get('/cart',                     [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{book}',         [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{item}',      [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/remove/{item}',    [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/coupon',             [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::delete('/cart/coupon',           [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

    // Stripe
    Route::get('/checkout/stripe',  [CheckoutController::class, 'create'])->name('checkout.stripe');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel',  [CheckoutController::class, 'cancel'])->name('checkout.cancel');

    // PayPal
    Route::get('/checkout/paypal',         [PaypalController::class, 'create'])->name('paypal.create');
    Route::get('/checkout/paypal/return',  [PaypalController::class, 'approveReturn'])->name('paypal.return');
    Route::get('/checkout/paypal/cancel',  [PaypalController::class, 'cancel'])->name('paypal.cancel');

    // Historique (user)
    Route::get('/orders',         [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
});

/*
|--------------------------------------------------------------------------
| Livres
|--------------------------------------------------------------------------
| IMPORTANT : déclarer 'create/edit' AVANT 'show' pour éviter le 404.
*/

// Index (public)
Route::get('books', [BookController::class, 'index'])->name('books.index');

// CRUD admin (create/store/edit/update/destroy)
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('books/create',        [BookController::class, 'create'])->name('books.create');
    Route::post('books',              [BookController::class, 'store'])->name('books.store');
    Route::get('books/{book}/edit',   [BookController::class, 'edit'])->name('books.edit');
    Route::put('books/{book}',        [BookController::class, 'update'])->name('books.update');
    Route::delete('books/{book}',     [BookController::class, 'destroy'])->name('books.destroy');
});

// Show (public) — placé APRÈS create/edit
Route::get('books/{book}', [BookController::class, 'show'])->name('books.show');

// Nouveautés
Route::get('/news', [BookController::class, 'news'])->name('books.news');

/*
|--------------------------------------------------------------------------
| Admin (messages + commandes)
|--------------------------------------------------------------------------
*/
// routes/web.php

Route::middleware(['auth','verified','role:admin'])->group(function () {
    Route::get('/admin/messages', [AdminMessageController::class,'index'])->name('admin.messages.index');
    Route::patch('/admin/messages/{message}/read', [AdminMessageController::class,'markAsRead'])->name('admin.messages.read');
    Route::patch('/admin/messages/{message}/unread', [AdminMessageController::class,'markAsUnread'])->name('admin.messages.unread'); // ✅
    Route::delete('/admin/messages/{message}', [AdminMessageController::class,'destroy'])->name('admin.messages.destroy');
});

// Commandes (si tu veux garder les vues détaillées admin)
Route::get('/admin/orders',         [AdminOrderController::class, 'index'])->name('admin.orders.index');
Route::get('/admin/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');

require __DIR__ . '/auth.php';
