<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\Item;


class CartItemController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');
}

    public function add(Request $request)
{
    $request->validate([
        'item_id' => 'required|exists:items,id',
    ]);

    $userId = Auth::id();

    $cartItem = CartItem::where('user_id', $userId)
                        ->where('item_id', $request->item_id)
                        ->first();

    if ($cartItem) {
        $cartItem->quantity += 1;
        $cartItem->save();
    } else {
        CartItem::create([
            'user_id' => $userId,
            'item_id' => $request->item_id,
            'quantity' => 1,
        ]);
    }

    return redirect()->route('cart.show')->with('success', '商品をカートに追加しました');

}
    public function show()
    {
        $userId = Auth::id();
        $cartItems = CartItem::with('item')->where('user_id', $userId)->get();

        return view('cart_item', compact('cartItems'));
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->quantity = $request->input('quantity');
        $cartItem->save();

        return redirect()->route('cart.show')->with('success', '数量を更新しました。');
    }

    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return redirect()->route('cart.show')->with('success', '商品をカートから削除しました。');
    }
}
