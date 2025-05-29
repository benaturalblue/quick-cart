<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\CartItemController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::middleware('api')->prefix('api')->group(function () {
    Route::get('/items', [ItemController::class, 'index']);
});

Route::middleware('api')->prefix('api')->group(function () {
    Route::post('/cart/add', [CartItemController::class, 'add'])->name('cart.add');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

