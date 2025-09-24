<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\OrderController as UserOrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\MessageController; // Formulaire Contact (public)

// Accueil = /books
Route::redirect('/', '/books')->name('home');


// Dashboard (protégé)
Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Compte (auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
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

    // Checkout Stripe
    Route::get('/checkout/stripe',  [CheckoutController::class, 'create'])->name('checkout.stripe');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel',  [CheckoutController::class, 'cancel'])->name('checkout.cancel');

    // Checkout PayPal
    Route::get('/checkout/paypal',         [PaypalController::class, 'create'])->name('paypal.create');
    Route::get('/checkout/paypal/return',  [PaypalController::class, 'approveReturn'])->name('paypal.return');
    Route::get('/checkout/paypal/cancel',  [PaypalController::class, 'cancel'])->name('paypal.cancel');

    // Historique (user)
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
});

/*
|--------------------------------------------------------------------------
| Livres
|--------------------------------------------------------------------------
| Public : index+show
| Admin  : create/store/edit/update/destroy
*/
Route::resource('books', BookController::class)->only(['index','show']);
Route::middleware(['auth','verified','role:admin'])->group(function () {
    Route::resource('books', BookController::class)->except(['index','show']);
});

// Route supplémentaire : Nouveautés
Route::get('/news', [BookController::class, 'news'])->name('books.news');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','verified','role:admin'])->group(function () {
    // Messages
    Route::get('/admin/messages',                 [AdminMessageController::class,'index'])->name('admin.messages.index');
    Route::patch('/admin/messages/{message}/read',[AdminMessageController::class,'markAsRead'])->name('admin.messages.read');
    Route::delete('/admin/messages/{message}',    [AdminMessageController::class,'destroy'])->name('admin.messages.destroy');

    // Commandes
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
});

require __DIR__.'/auth.php';