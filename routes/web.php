<?php

use Illuminate\Support\Facades\Route;
use App\Models\Item;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    $items = Item::all();
    return view('welcome', compact('items'));
});

Route::get('/items/{id}', function ($id) {
    $item = Item::findOrFail($id);
    return view('show_item', compact('item'));
})->name('show_item');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

