<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light border-bottom" style="background-color: #F3f3f3;">
            <div class="container-fluid">
                <a href="{{ route('welcome') }}">
                    <img src="{{ asset('images/quickcart_logo.jpg') }}" alt="QuickCart ロゴ" style="width: 300px; height: 120px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="container">
                    <ul class="navbar-nav ms-auto d-flex align-items-center gap-2 mb-2 mb-lg-0">
                        @guest
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-outline-light" href="/register"
                                    style="background-color: #8c94cc !important; border-color: #8c94cc;">新規登録はこちらから</a>
                                </li>
                            @endif

                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="btn btn-outline-light" href="/login"
                                    style="background-color: #8c94cc !important; border-color: #8c94cc;">ログイン</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->nickname }}様
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('home') }}">
                                        {{ __('マイページ') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        {{ __('ログアウト') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="text-center" style="background-color: #8c94cc;">
            <div class="container p-4 pb-0">
                <section>
                    <p class="d-flex justify-content-center align-items-center">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-rounded btn-spaced">新規登録</a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-rounded btn-spaced">ログイン</a>
                            <a href="/casteria/create" class="btn btn-outline-light btn-rounded">お問い合わせ</a>
                        @else
                            <a href="{{ route('home') }}" class="btn btn-outline-light btn-rounded btn-spaced">マイページ</a>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();"
                                        class="btn btn-outline-light btn-rounded btn-spaced">ログアウト</a>
                            <a href="/casteria/create" class="btn btn-outline-light btn-rounded">お問い合わせ</a>
                        @endguest
                    </p>
                </section>
            </div>
            <div class="text-center p-3" style="background-color: #f3f3f3;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 mx-auto">
                            <a href="{{ route('welcome') }}">
                                <img src="{{ asset('images/quickcart_logo.jpg') }}" alt="QuickCart ロゴ" style="width: 200px; height: 90px;">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
