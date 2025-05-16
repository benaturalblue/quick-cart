@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('新規登録') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}"class="h-adr">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('名前') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nickname" class="col-md-4 col-form-label text-md-end">{{ __('ニックネーム') }}</label>

                            <div class="col-md-6">
                                <input id="nickname" type="text" class="form-control @error('nickname') is-invalid @enderror" name="nickname" value="{{ old('nickname') }}" required autocomplete="name" autofocus>

                                @error('nickname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('メールアドレス') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <span class="p-country-name" style="display:none;">Japan</span>
                        <input type="hidden" name="address" id="address">
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">郵便番号</label>
                            <div class="col-md-6">
                                〒<input type="text" name="postal_code1" class="p-postal-code form-control d-inline-block w-auto" size="3" maxlength="3" required>
                                -<input type="text" name="postal_code2" class="p-postal-code form-control d-inline-block w-auto" size="4" maxlength="4" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">都道府県</label>
                            <div class="col-md-6">
                                <input type="text" name="region" class="p-region form-control" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">市区町村</label>
                            <div class="col-md-6">
                                <input type="text" name="locality" class="p-locality form-control" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">以降の住所</label>
                            <div class="col-md-6">
                                <input type="text" name="street" class="p-street-address form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="number" class="col-md-4 col-form-label text-md-end">{{ __('電話番号') }}</label>

                            <div class="col-md-6">
                                <input id="number" type="text" class="form-control @error('number') is-invalid @enderror" name="number" value="{{ old('number') }}" required autocomplete="email">

                                @error('number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('パスワード') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('パスワード確認') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('登録') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fields = ['postal_code1', 'postal_code2', 'region', 'locality', 'street', 'extended'];

        fields.forEach(name => {
            const el = document.getElementsByName(name)[0];
            if (el) {
                el.addEventListener('input', updateAddress);
            }
        });

        function updateAddress() {
            const postal1 = document.getElementsByName('postal_code1')[0].value;
            const postal2 = document.getElementsByName('postal_code2')[0].value;
            const region = document.getElementsByName('region')[0].value;
            const locality = document.getElementsByName('locality')[0].value;
            const street = document.getElementsByName('street')[0].value;
            const extended = document.getElementsByName('extended')[0].value;

            const fullAddress = `〒${postal1}-${postal2} ${region}${locality}${street}${extended}`;
            document.getElementById('address').value = fullAddress;
        }
    });
</script>
@endsection
