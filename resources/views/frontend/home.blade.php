<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Bank Imam</title>
    <meta name="description" content="Bank imam">
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <link rel="stylesheet" href="{{ asset('template-fe/assets/css/style.css') }}">
    {{-- <link rel="manifest" href="{{asset('template-fe/__manifest.json')}}"> --}}

</head>

<body>

    <!-- loader -->
    <div id="loader">
        <img src="{{ asset('template-fe/assets/img/bank.svg') }}" alt="icon" class="loading-icon">
    </div>
    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader bg-primary text-light" style="max-width: 640px; margin:0 auto;">
        <div class="left">
            <h4>@yield('title', 'Default Title')</h4> <!-- Jika tidak ada yield, tampilkan 'Default Title' -->
        </div>
        <div class="right">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <a href="#" class="headerButton" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <ion-icon name="exit-outline"></ion-icon>
                </a>
            </form>
           
        </div>
    </div>
    <!-- App Capsule -->
    <div id="appCapsule" style="max-width: 640px; margin:0 auto; background-color: #f5f6f8;">

        <!-- Wallet Card -->
        @php
            use Carbon\Carbon;

            $hour = Carbon::now()->hour;
            if ($hour < 11) {
                $greeting = 'Selamat Pagi';
            } elseif ($hour < 15) {
                $greeting = 'Selamat Siang';
            } elseif ($hour < 19) {
                $greeting = 'Selamat Sore';
            } else {
                $greeting = 'Selamat Malam!';
            }

        @endphp
        <div class="section wallet-card-section pt-1">
            <div class="profile-wrapper d-flex gap-3">
                <div class="name-wrapper">
                    <span style="color:#ffffff; font-size:18px; font-weight:bold;">{{ $greeting }},
                        <span>{{ Auth::user()->name }}.</span></span>
                    <p style="color:#ffffff; font-size:18px; font-weight:bold;">Ayo Kumpulkan Sampah dan Setorkan!</p>
                </div>

            </div>
            <div class="wallet-card ">
                <!-- Balance -->
                @livewire('saldo-info')
                <!-- * Balance -->
                <!-- Wallet Footer -->
                <div class="wallet-footer">
                    <div class="item">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#withdrawActionSheet">
                            <div class="icon-wrapper bg-outline-success">
                                <ion-icon name="swap-vertical"></ion-icon>
                            </div>
                            <strong>Tarik Tunai</strong>
                        </a>
                    </div>
                    <div class="item">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#sendActionSheet">
                            <div class="icon-wrapper">
                                <ion-icon name="sync"></ion-icon>
                            </div>
                            <strong>Tukar Point</strong>
                        </a>
                    </div>
                    <div class="item">
                        <a href="app-cards.html">
                            <div class="icon-wrapper ">
                                <ion-icon name="time"></ion-icon>
                            </div>
                            <strong>Riwayat</strong>
                        </a>
                    </div>

                </div>
                <!-- * Wallet Footer -->
            </div>
        </div>
        <!-- Wallet Card -->

        <div class="section  mb-3">
            <div class="row mt-2">
                <div class="col-12">
                    <a href="{{route('setor-sampah')}}">
                        <div class="stat-box d-flex align-items-center justify-content-between shadowed"
                            style="padding: 10px 15px;">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset('template-fe/assets/img/box.png') }}" alt=""
                                    style="max-width: 50px;">
                                <div class="mt-2">
                                    <h2 class="title">Setor Sampah</h2>
                                    <p class="text-muted" style="font-size:10px;">Timbang sampah Anda untuk mengetahui
                                        jumlah yang dapat ditukar.</p>
                                </div>
                            </div>
                            <ion-icon name="arrow-forward-circle"
                                style="width: 40px; height: 40px; color: #51D991;"></ion-icon>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        @livewire('transaction-list')

        <div class="section full mb-3">
            <div class="section-heading padding">
                <h2 class="title">Berita terbaru</h2>
                <a href="{{ route('listBlog') }}" class="link">Lihat semua</a>
            </div>

            <!-- carousel artikel -->
            @if ($articles->isNotEmpty())
                <div class="carousel-single splide" id="splide02">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach ($articles as $article)
                                <li class="splide__slide">
                                    <a href="{{ route('detailBlog', $article->slug) }}">
                                        <div class="card" style="width: 100%; height: 300px;">
                                            <div class="image-wrapper-blog">

                                                <img src="{{ asset($article->image) }}" class="card-img-top"
                                                    alt="image">
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $article->title }}</h5>
                                                <p class="card-text">{{ Str::limit($article->content, 100) }}</p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @else
                <div class="title p-3">Tunggu berita yang akan datang</div>
            @endif
            <!-- * carousel artikel -->
        </div>




        <div class="section full mt-4 mb-3">
            <div class="section-heading padding">
                <h2 class="title">Rewards</h2>
                <a href="{{ route('listRewards') }}" class="link">Lihat semua</a>
            </div>

            @if ($rewards->count() > 1)
                <!-- carousel multiple -->
                <div class="carousel-multiple splide splide--loop splide--ltr splide--draggable is-active"
                    id="splide03" style="visibility: visible;">
                    <div class="splide__track" id="splide03-track" style="padding-left: 16px; padding-right: 16px;">
                        @if ($rewards->isNotEmpty())
                            <ul class="splide__list" id="splide03-list" style="transform: translateX(-2224px);">

                                @foreach ($rewards as $reward)
                                    <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1"
                                        style="margin-right: 16px; width: 240px;">

                                        <div class="blog-card">
                                            <div class="image-wrapper d-flex justify-content-center">
                                                <img src="{{ asset($reward->image) }}" alt="image"
                                                    class="imaged w86">
                                            </div>
                                            <div class="text-wrapper p-2">
                                                <div class="mb-1 text-center">
                                                    <h5>{{ $reward->name }}</h5>
                                                    <span
                                                        class="badge badge-warning">{{ number_format($reward->points, 0, ',', '.') }}
                                                        Poin</span>
                                                </div>
                                            </div>
                                        </div>

                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="title p-3">Belum ada rewards yang tersedia</div>
                        @endif
                    </div>
                </div>
                <!-- * carousel multiple -->
            @elseif ($rewards->count() == 1)
                <div class="single-reward text-center">
                    <div class="blog-card mx-auto" style="max-width: 140px;">
                        <div class="image-wrapper d-flex justify-content-center">
                            <img src="{{ asset($rewards->first()->image) }}" alt="image" class="imaged w86">
                        </div>
                        <div class="text-wrapper p-2">
                            <div class="mb-1 text-center">
                                <h5>{{ $rewards->first()->name }}</h5>
                                <span
                                    class="badge badge-warning">{{ number_format($rewards->first()->points, 0, ',', '.') }}
                                    Poin</span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="title p-3">Belum ada rewards yang tersedia</div>
            @endif



        </div>

    </div>
    <!-- * App Capsule -->
    <!-- App Bottom Menu -->
    <div class="appBottomMenu" style="max-width: 640px; margin:0 auto;">
        <a href="{{ route('home') }}" class="item {{ Route::is('home') ? 'active' : '' }}">
            <div class="col">
                <ion-icon name="home-outline"></ion-icon>
                <strong>Home</strong>
            </div>
        </a>
        <a href="{{ route('listBlog') }}" class="item">
            <div class="col">
                <ion-icon name="newspaper-outline"></ion-icon>
                <strong>Info</strong>
            </div>
        </a>
        <a href="app-components.html" class="item">
            <div class="col">
                <ion-icon name="podium"></ion-icon>
                <strong>Peringkat</strong>
            </div>
        </a>
        <a href="/profile" class="item">
            <div class="col">
                <ion-icon name="person-circle-outline"></ion-icon>
                <strong>Profile</strong>
            </div>
        </a>
    </div>
    <!-- * App Bottom Menu -->






    <!-- Android Add to Home Action Sheet -->
    <div class="modal inset fade action-sheet android-add-to-home" id="android-add-to-home-screen" tabindex="-1"
        role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add to Home Screen</h5>
                    <a href="#" class="close-button" data-bs-dismiss="modal">
                        <ion-icon name="close"></ion-icon>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="action-sheet-content text-center">
                        <div class="mb-1">
                            <img src="assets/img/icon/192x192.png" alt="image" class="imaged w64 mb-2">
                        </div>
                        <div>
                            Install <strong>Finapp</strong> on your Android's home screen.
                        </div>
                        <div>
                            Tap <ion-icon name="ellipsis-vertical"></ion-icon> and Add to homescreen.
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-primary btn-block" data-bs-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Android Add to Home Action Sheet -->




    <!-- ========= JS Files =========  -->
    <!-- Bootstrap -->
    <script src="{{ asset('template-fe/assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="{{ asset('template-fe/assets/js/plugins/splide/splide.min.js') }}"></script>
    <!-- Base Js File -->
    <script src="{{ asset('template-fe/assets/js/base.js') }}"></script>


    <script>
        // Add to Home with 2 seconds delay.
        AddtoHome("2000", "once");
    </script>

</body>

</html>
