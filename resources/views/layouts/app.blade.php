<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light border-bottom" style="background-color: #F3f3f3;">
            <div class="container-fluid">
                <img src="{{ asset('images/quickcart_logo.jpg') }}" alt="" style="width: 300px; height: 120px;">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="container">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto d-flex align-items-center gap-2 mb-2 mb-lg-0">
                        <!-- Authentication Links -->
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
    </div>
</body>
</html>
