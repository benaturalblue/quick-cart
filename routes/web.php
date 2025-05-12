<?php

use Illuminate\Support\Facades\Route;
use App\Models\Item;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;


Route::get('/', function () {
    $items = Item::all();
    return view('welcome', compact('items'));
})->name('welcome');

Route::get('/items/{id}', function ($id) {
    $item = Item::findOrFail($id);
    return view('show_item', compact('item'));
})->name('show_item');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::post('add', [CartItemController::class, 'add'])->name('add');
    Route::get('/', [CartItemController::class, 'show'])->name('show');
    Route::put('{id}', [CartItemController::class, 'update'])->name('update');
    Route::delete('{id}', [CartItemController::class, 'destroy'])->name('destroy');
});

Route::prefix('order')->middleware('auth')->name('order.')->group(function () {
    Route::get('confirm', [OrderController::class, 'confirm'])->name('confirm');
    Route::post('payment', [OrderController::class, 'payment'])->name('payment');
    Route::get('success', function () {
        return view('order.order_success');
    })->name('success');
});
