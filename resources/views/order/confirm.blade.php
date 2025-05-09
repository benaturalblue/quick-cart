@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">注文確認画面</h2>

    <div class="card mb-4">
        <div class="card-body">
        <h4>お届け先情報</h4>
        <p><strong>お名前:</strong> {{ $user->name }}</p>
        <p><strong>ニックネーム:</strong> {{ $user->nickname }}</p>
        <p><strong>メールアドレス:</strong> {{ $user->email }}</p>
        <p><strong>住所:</strong> {{ $user->address }}</p>
        <p><strong>電話番号:</strong> {{ $user->number }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
        <h4>ご注文内容</h4>
        @php $total = 0; @endphp
        @foreach ($cartItems as $cartItem)
            @php
                $subtotal = $cartItem->item->price * $cartItem->quantity;
                $total += $subtotal;
            @endphp
            <div class="border-bottom py-2">
                <strong>{{ $cartItem->item->name }}</strong><br>
                <img src="{{ asset('images/' . $cartItem->item->image) }}" class="img-fluid my-2" style="max-width: 100px;"><br>
                価格: ¥{{ $cartItem->item->price }}<br>
                数量: {{ $cartItem->quantity }}<br>
                小計: ¥{{ $subtotal }}
            </div>
        @endforeach
        
        <h5 class="text-end mt-4">合計金額: ¥{{ $total }}</h5>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <div class="container">
        <h2>お支払い情報入力</h2>

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form id="payment-form" action="{{ route('order.payment') }}" method="POST">
            @csrf
            <input type="hidden" name="payment_method" id="payment-method">
            <input type="hidden" name="total" value="{{ $total }}">

            <div id="card-element" class="my-3"></div>
            <button type="submit" class="btn btn-primary">注文を確定する</button>
        </form>
    </div>

    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');
        const elements = stripe.elements();
        const card = elements.create('card');
        card.mount('#card-element');

        const form = document.getElementById('payment-form');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const { setupIntent, error } = await stripe.confirmCardSetup(
                '{{ $intent->client_secret }}',
                {
                    payment_method: {
                        card: card,
                        billing_details: {
                            name: '{{ Auth::user()->name }}'
                        }
                    }
                }
            );

            if (error) {
                alert(error.message);
            } else {
                document.getElementById('payment-method').value = setupIntent.payment_method;
                form.submit();
            }
        });
    </script>


</div>
@endsection
