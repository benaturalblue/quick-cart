@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h2 class="mb-4">ご注文ありがとうございます！</h2>
    <p class="lead">ご注文が正常に完了しました。</p>
    <p>ご登録のメールアドレスに確認メールを送信しました。</p>

    <div class="mt-4">
        <a href="{{ route('home') }}" class="btn btn-primary">マイページへ</a>
    </div>
</div>
@endsection

