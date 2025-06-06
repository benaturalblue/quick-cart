<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';
require base_path('routes/api.php');

Route::middleware(['web', 'auth'])->prefix('api')->group(function () {
    Route::get('/user', function () {
        return Auth::user(); // ログイン中のユーザー情報を返す
    });

    Route::get('/cart/show', [CartItemController::class, 'show'])->name('cart.show');
});

Route::middleware(['web', 'auth:sanctum'])->prefix('api')->group(function () {
    Route::post('/order/submit', [OrderController::class, 'apiPayment'])->name('order.submit');
});

