@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="text-center" style="max-width: 30%;">
        <h1>{{ $item->name }}</h1>
        <img src="{{ asset('images/' . $item->image) }}" alt="{{ $item->name }}" class="img-fluid mb-3">
        <p><strong>価格：</strong> ¥{{ $item->price }}</p>
        <p>{{ $item->description ?? '説明はありません。' }}</p>

        <div class="d-flex gap-2 justify-content-center">
            <form method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <button type="submit" class="btn btn-primary">カートに入れる</button>
            </form>
            <a href="{{ url('/') }}" class="btn btn-secondary">戻る</a>
        </div>
    </div>
</div>

@endsection
