@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">カートに入っている商品</h2>

    <div class="row justify-content-center">
        <div class="col-md-8">
            @php $total = 0; @endphp

            @foreach ($cartItems as $cartItem)
                @php
                    $subtotal = $cartItem->item->price * $cartItem->quantity;
                    $total += $subtotal;
                @endphp

                <div class="card mb-3">
                    <div class="card-body text-center">
                        <strong>{{ $cartItem->item->name }}</strong><br>
                        <img src="{{ asset('images/' . $cartItem->item->image) }}" class="img-fluid my-2" style="max-width: 200px;"><br>
                        価格: ¥{{ $cartItem->item->price }}<br>
                        小計: ¥{{ $subtotal }}<br>

                        <form action="{{ route('cart.update', $cartItem->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="1" style="width: 60px;">
                            <button type="submit" class="btn btn-outline-primary">更新</button>
                        </form>

                        <form action="{{ route('cart.destroy', $cartItem->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">削除</button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="text-center mt-4">
                <h4 class="text-end mt-4">合計金額: ¥{{ $total }}</h4>
                <a href="{{ route('order.confirm') }}" class="btn btn-primary">注文確認へ進む</a>
            </div>

        </div>
    </div>
</div>
<div class="text-center p-3" style="background-color: #f3f3f3;">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mx-auto">
                <img src="{{ asset('images/quickcart_logo.jpg') }}" alt="" class="img-fluid" style="max-width: 200px;">
            </div>
        </div>
    </div>
</div>
@endsection
