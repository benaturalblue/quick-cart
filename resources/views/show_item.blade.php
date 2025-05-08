@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="text-center" style="max-width: 600px;">
        <h1>{{ $item->name }}</h1>
        <img src="{{ asset('images/' . $item->image) }}" alt="{{ $item->name }}" class="img-fluid mb-3">
        <p><strong>価格：</strong> ¥{{ $item->price }}</p>
        <p>{{ $item->description ?? '説明はありません。' }}</p>
        <button class="btn btn-quickcart">カートに入れる</button>
        <a href="{{ url('/') }}" class="btn btn-secondary mb-4">戻る</a>
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
