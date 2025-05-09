<?php

use Illuminate\Support\Facades\Route;
use App\Models\Item;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\OrderController;


Route::get('/', function () {
    $items = Item::all();
    return view('welcome', compact('items'));
})->name('welcome');

Route::get('/items/{id}', function ($id) {
    $item = Item::findOrFail($id);
    return view('show_item', compact('item'));
})->name('show_item');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/cart/add', [CartItemController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartItemController::class, 'show'])->name('cart.show');

Route::put('/cart/{id}', [CartItemController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartItemController::class, 'destroy'])->name('cart.destroy');

Route::get('/order/confirm', [OrderController::class, 'confirm'])->name('order.confirm')->middleware('auth');

Route::post('/order/payment', [OrderController::class, 'payment'])->name('order.payment')->middleware('auth');

Route::get('/order/success', function () {
    return view('order.order_success');
})->name('order.success');

