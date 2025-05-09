<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Exception;
use Stripe\Climate\Order;
use Stripe\PaymentIntent;

class OrderController extends Controller
{
    public function confirm()
{
    $user = Auth::user();

    if (!$user->hasStripeId()) {
        $user->createAsStripeCustomer();
    }

    $intent = $user->createSetupIntent();

    $cartItems = \App\Models\CartItem::with('item')->where('user_id', $user->id)->get();

    return view('order.confirm', compact('user', 'intent', 'cartItems'));
}


public function payment(Request $request)
{
    $user = Auth::user();

    if (!$user->hasStripeId()) {
        $user->createAsStripeCustomer();
    }

    $paymentMethodId = $request->input('payment_method');
    $amount = intval($request->input('total'));

    if (!$paymentMethodId || !$amount) {
        return redirect()->back()->withError('カード情報または金額が正しく送信されていません。');
    }

    Stripe::setApiKey(config('cashier.secret'));

    try {
        $intent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'jpy',
            'customer' => $user->stripe_id,
            'payment_method' => $paymentMethodId,
            'confirm' => true,
            'off_session' => true,
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never',
            ],
        ]);

        $order = \App\Models\Order::create([
            'user_id' => $user->id,
            'total_price' => $amount,
        ]);

        // カート商品を取得
        $cartItems = \App\Models\CartItem::with('item')->where('user_id', $user->id)->get();

        foreach ($cartItems as $cartItem) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $cartItem->item_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->item->price,
            ]);
        }

        \App\Models\CartItem::where('user_id', $user->id)->delete();

        return redirect()->route('order.success');
    } catch (\Exception $e) {
        return back()->withError('決済に失敗しました: ' . $e->getMessage());
    }
}

}
