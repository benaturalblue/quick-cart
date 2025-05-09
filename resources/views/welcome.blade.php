@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="container py-4">
            <div class="row g-4">
                @foreach ($items as $item)
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card h-100 text-center shadow-sm mb-4">
                            <img src="{{ asset('images/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->name }}</h5>
                                <p class="card-text text-muted">¥{{ number_format($item->price) }}</p>
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <button type="submit" class="btn btn-outline-primary btn-login">カートに入れる</button>
                                </form>
                                <a href="{{ route('show_item', $item->id) }}" class="btn btn-outline-primary">詳細を見る</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
