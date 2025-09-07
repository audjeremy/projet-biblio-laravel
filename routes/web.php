<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MessageController;

// Redirige la racine vers /books
Route::redirect('/', '/books');

Route::resource('books', BookController::class);           // index/create/store/show/edit/update/destroy
Route::get('/news', [BookController::class, 'news'])->name('books.news');

Route::get('/contact', [MessageController::class, 'create'])->name('contact');
Route::post('/contact', [MessageController::class, 'store'])->name('messages.store');
Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');