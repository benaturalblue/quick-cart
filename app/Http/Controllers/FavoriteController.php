<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;


class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
        ]);

        $user = $request->user();
        $itemId = $request->item_id;

        if ($user->favoriteItems()->where('item_id', $itemId)->exists()) {
            return response()->json(['message' => 'すでにお気に入り登録済みです。'], 409);
        }

        $user->favoriteItems()->attach($itemId);

        return response()->json(['message' => 'お気に入り登録しました。']);
    }
}


