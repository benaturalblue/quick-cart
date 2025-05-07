<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>QuickCart</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <style>
        body {
            background-color: #f3f3f3;
        }

        .btn-spaced {
            margin-right: 30px;
        }

        .navbar-nav .nav-item {
            margin: 0 20px 0 10px;
        }
        .btn-quickcart {
            background-color: #8c94cc;
            color: #fff;
            border: none;
        }
        .btn-quickcart:hover {
            background-color: #7b83b8;
            color: #fff;
        }
    </style>

</head>

<body class="antialiased">
    <nav class="navbar navbar-expand-lg navbar-light border-bottom" style="background-color: #F3f3f3;">
        <div class="container-fluid">
            <img src="{{ asset('images/quickcart_logo.jpg') }}" alt="" style="width: 300px; height: 120px;">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto d-flex align-items-center gap-2 mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="btn btn-outline-light" href="/register"
                            style="background-color: #8c94cc !important; border-color: #8c94cc;">新規登録はこちらから</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light" href="/login"
                            style="background-color: #8c94cc !important; border-color: #8c94cc;">ログイン</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


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
                                <button class="btn btn-quickcart">カートに入れる</button>
                                <a href="{{ route('show_item', $item->id) }}" class="btn btn-outline-primary">詳細を見る</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>




    <section class="">
        <!-- Footer -->
        <footer class="text-center" style="background-color: #8c94cc;">
            <!-- Grid container -->
            <div class="container p-4 pb-0">
                <!-- Section: CTA -->
                <section class="">
                    <p class="d-flex justify-content-center align-items-center">
                        <a href="/register" class="btn btn-outline-light btn-rounded btn-spaced">新規登録</a>
                        <a href="/login" class="btn btn-outline-light btn-rounded btn-spaced">ログイン</a>
                        <a href="/casteria/create" class="btn btn-outline-light btn-rounded">お問い合わせ</a>
                    </p>

                </section>
                <!-- Section: CTA -->
            </div>
            <!-- Grid container -->

            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: #f3f3f3;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 mx-auto">
                            <img src="{{ asset('images/quickcart_logo.jpg') }}" alt="" class="img-fluid" style="max-width: 200px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
        </footer>
        <!-- Footer -->
    </section>




</body>

</html>
