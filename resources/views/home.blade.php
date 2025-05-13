@extends('layouts.app')

@section('content')
<div class="container">
    <h2>購入履歴</h2>

    @forelse($orders as $order)
        <div class="card mb-4">
            <div class="card-header">
                注文日: {{ $order->created_at->format('Y年m月d日 H:i') }}｜合計金額: ¥{{ number_format($order->total_price) }}
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($order->orderItems as $orderItem)
                        <li class="list-group-item">
                            {{ $orderItem->item->name }} / ¥{{ number_format($orderItem->item->price) }} × {{ $orderItem->quantity }}個
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @empty
        <p>購入履歴がありません。</p>
    @endforelse

    <h2 class="mb-4">お客様登録情報</h2>

    <div class="card mb-4">
        <div class="card-body">
        <p><strong>お名前:</strong> {{ Auth::user()->name }}</p>
        <p><strong>ニックネーム:</strong> {{ Auth::user()->nickname }}</p>
        <p><strong>メールアドレス:</strong> {{ Auth::user()->email }}</p>
        <p><strong>住所:</strong> {{ Auth::user()->address }}</p>
        <p><strong>電話番号:</strong> {{ Auth::user()->number }}</p>
        </div>
    </div>

</div>
@endsection
