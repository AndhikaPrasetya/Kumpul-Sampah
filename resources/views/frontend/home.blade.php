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
    <link rel="stylesheet" href="{{ asset('/template/plugins/fontawesome-free/css/all.min.css') }}">
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
                <a href="#" class="headerButton"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
                        <a href="{{ route('tarik-tunai') }}">
                            <div class="icon-wrapper bg-outline-success">
                                <ion-icon name="swap-vertical"></ion-icon>
                            </div>
                            <strong>Tarik Tunai</strong>
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('listRewards') }}">
                            <div class="icon-wrapper">
                                <ion-icon name="sync"></ion-icon>
                            </div>
                            <strong>Tukar Point</strong>
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('transaksiFrontend.index') }}">
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
                    <a href="{{ route('setor-sampah') }}">
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
                                style="width: 40px; height: 40px; color: #16a34a;"></ion-icon>
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

            @if ($articles->isNotEmpty())
                @if ($articles->count() === 1)
                    <div class="px-2">
                        @php $article = $articles->first(); @endphp
                        <div class="bg-white rounded-3 shadow-sm border border-light overflow-hidden">
                            <a href="{{ route('detailBlog', $article->slug) }}" class="text-decoration-none text-dark">
                                <img src="{{ asset($article->thumbnail) }}" alt="{{ $article->title }}" class="w-100"
                                    style="height: 160px; object-fit: cover;" />
                                <div class="p-3">
                                    <h5 class="fw-bold text-dark mt-2">{{ $article->title }}</h5>
                                    <p class="text-muted small mt-1 text-truncate"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit($article->content, 100) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span
                                            class="text-muted small">{{ $article->created_at->format('d M Y') }}</span>
                                        <button class="btn btn-link text-success p-0 fw-medium small">Baca
                                            Selengkapnya</button>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="carousel-full p-3 splide" id="splide02">
                        <div class="splide__track">
                            <ul class="splide__list gap-3">
                                @foreach ($articles as $article)
                                    <li class="splide__slide">
                                        <a href="{{ route('detailBlog', $article->slug) }}">
                                            <div class="card" style="width: 100%; height: 300px;">
                                                <div style="height: 180px; overflow: hidden;">
                                                    <img src="{{ asset($article->thumbnail) }}" class="card-img-top"
                                                        alt="image"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
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

                @endif
            @else
                <div class="p-2 d-flex align-items-center justify-content-center ">
                    <div class="text-center text-secondary">
                        <i class="fas fa-newspaper fs-1 text-muted"></i>
                        <p>Tunggu berita yang akan datang</p>
                    </div>
                </div>
            @endif

            <!-- * carousel artikel -->
        </div>




        <div class="section full mt-4 mb-3">
            <div class="section-heading padding">
                <h2 class="title">Rewards</h2>
                <a href="{{ route('listRewards') }}" class="link">Lihat semua</a>
            </div>


            <!-- Carousel multiple -->
            <div class="carousel-full p-2 splide splide--loop splide--ltr splide--draggable is-active" id="splide03"
                style="visibility: visible;">
                <div class="splide__track" id="splide03-track" style="padding-left: 16px; padding-right: 16px;">
                    @if ($rewards->isEmpty())
                        <div class="p-2 d-flex align-items-center justify-content-center">
                            <div class="text-center text-secondary">
                                <i class="fas fa-gift fs-1 mb-3 text-muted"></i>
                                <p>Belum ada rewards yang tersedia</p>
                            </div>
                        </div>
                    @elseif ($rewards->count() == 1)
                        @php
                            $reward = $rewards->first();
                            $needPoints = max(0, $reward->points - $currentPoints);
                            $progress = min(100, ($currentPoints / $reward->points) * 100);
                        @endphp

                        <a href="{{ route('detailReward', $reward->id) }}" class="text-decoration-none">
                            <div class="card shadow-sm border w-full">
                                <div class="position-relative">
                                    <img src="{{ asset($reward->image) }}" alt="{{ $reward->name }}"
                                        class="card-img-top" style="height: 160px; object-fit: contain;">
                                </div>
                                <div class="card-body p-2">
                                    <h6 class="card-title fw-bold text-dark text-truncate">{{ $reward->name }}</h6>
                                    <div class="d-flex align-items-center mt-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-coins text-warning me-1"></i>
                                            <span
                                                class="fw-bold text-dark small">{{ number_format($reward->points, 0, ',', '.') }}
                                                points</span>
                                        </div>
                                        <span class="text-muted small">Klaim:
                                            {{ $currentPoints }}/{{ $reward->points }}</span>
                                    </div>
                                    <div class="mt-2 d-flex align-items-center justify-content-between">
                                        <div class="progress w-75" style="height: 4px;">
                                            <div class="progress-bar bg-success"
                                                style="width: {{ $progress }}%;"></div>
                                        </div>
                                        <p class="text-muted small">{{ round($progress, 2) }}% klaim</p>
                                    </div>
                                    <button class="btn btn-success w-100 p-2 btn-sm mt-2">Tukarkan</button>
                                </div>
                            </div>
                        </a>
                    @else
                        <ul class="splide__list gap-3" id="splide03-list" style="transform: translateX(-2224px);">
                            @foreach ($rewards as $reward)
                                @php
                                    $needPoints = max(0, $reward->points - $currentPoints);
                                    $progress = min(100, ($currentPoints / $reward->points) * 100);
                                @endphp
                                <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1"
                                    style="margin-right: 16px; width: 240px;">
                                    <a href="{{ route('detailReward', $reward->id) }}" class="text-decoration-none">
                                        <div class="card shadow-sm border w-full">
                                            <div class="position-relative">
                                                <img src="{{ asset($reward->image) }}" alt="{{ $reward->name }}"
                                                    class="card-img-top" style="height: 160px; object-fit: contain;">
                                            </div>
                                            <div class="card-body p-2">
                                                <h6 class="card-title fw-bold text-dark text-truncate">
                                                    {{ $reward->name }}</h6>
                                                <div class="d-flex align-items-center justify-content-between mt-2">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-coins text-warning me-1"></i>
                                                        <span
                                                            class="fw-bold text-dark small">{{ number_format($reward->points, 0, ',', '.') }}
                                                            points</span>
                                                    </div>
                                                    <span class="badge badge-success">Stok: 20</span>
                                                </div>
                                                <div class="mt-2 d-flex align-items-center justify-content-between">
                                                    <div class="progress w-75" style="height: 4px;">
                                                        <div class="progress-bar bg-success"
                                                            style="width: {{ $progress }}%;"></div>
                                                    </div>
                                                    <span class="text-muted small">{{ round($progress, 2) }}%
                                                        klaim</span>
                                                </div>
                                                <button class="btn btn-success w-100 p-2 btn-sm mt-2 btn-tukarkan"
                                                    data-stock="{{ $reward->stock }}">
                                                    Tukarkan
                                                </button>


                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach

                        </ul>
                    @endif
                </div>
            </div>
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
        <a href="{{ route('leaderboard') }}" class="item">
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
                        <div>
                            Install <strong>Bank Imam</strong> on your Android's home screen.
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
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".btn-tukarkan").forEach(button => {
                let stock = parseInt(button.getAttribute("data-stock"));

                if (stock === 0) {
                    button.classList.add("btn-secondary", "disabled"); // Tambah kelas disabled
                    button.classList.remove("btn-success"); // Hapus warna hijau
                    button.innerText = "Habis"; // Ubah teks tombol
                }
            });
        });
    </script>

    <script>
        // Add to Home with 2 seconds delay.
        AddtoHome("2000", "once");
    </script>

</body>

</html>
