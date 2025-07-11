<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>QuickCart</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg border-bottom navbar-custom">
            <div class="container-fluid">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/quickcart_logo.jpg') }}" class="navbar-logo">
                </a>
                <div class="ms-auto">
                    <ul class="navbar-nav gap-4">
                        @guest
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="btn btn-outline-dark" href="/register">新規登録はこちらから</a>
                        </li>
                        @endif
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="btn btn-outline-dark me-4" href="/login">ログイン</a>
                            </li>
                        @endif

                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle me-4" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->nickname }}様
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('cart.show') }}">
                                        {{ __('カートを見る') }}
                                    </a>
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

        <footer>
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
            <div class="footer-logo text-center p-3">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 mx-auto">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('images/quickcart_logo.jpg') }}">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" async></script>
</body>
</html>
