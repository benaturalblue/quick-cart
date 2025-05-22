<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FavoriteController;
use Illuminate\Http\Request;
use App\Models\Item;


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/favorites', [FavoriteController::class, 'store']);
});

Route::post('/login-token', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (!auth()->attempt($credentials)) {
        return response()->json(['message' => '認証に失敗しました'], 401);
    }

    $token = auth()->user()->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token]);
});

Route::get('/items', function () {
    return Item::all();
});
