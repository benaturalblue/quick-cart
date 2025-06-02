<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\CartItemController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\OrderController;


Route::middleware('api')->prefix('api')->group(function () {
    Route::get('/items', [ItemController::class, 'index']);
});

Route::middleware('api')->prefix('api')->group(function () {
    Route::post('/cart/add', [CartItemController::class, 'add'])->name('cart.add');
    Route::post('/order/confirm', [OrderController::class, 'confirm'])->name('order.confirm');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

