<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {
    Route::get('/items', [ItemController::class, 'index']);
});
