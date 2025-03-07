@extends('layouts.layout-fe')
@section('content')
    <!-- App Capsule -->
    <div id="appCapsule">

        <!-- Wallet Card -->
        @php
            use Carbon\Carbon;

            $hour = Carbon::now()->hour;
            if ($hour < 12) {
                $greeting = 'Selamat Pagi';
            } elseif ($hour < 18) {
                $greeting = 'Selamat Sore';
            } else {
                $greeting = 'Selamat Malam!';
            }
        @endphp
        <div class="section wallet-card-section pt-1">
            <div class="profile-wrapper d-flex gap-3">
                <div class="name-wrapper">
                    <span style="color:#ffffff; font-size:18px; font-weight:bold;">{{ $greeting }}, <span>{{Auth::user()->name}}.</span>
                        👋 </span>
                    <p style="color:#ffffff; font-size:18px; font-weight:bold;">Ayo Kumpulkan Sampah dan Setorkan!</p>
                </div>

            </div>
            <div class="wallet-card ">
                <!-- Balance -->
                <div class="balance">
                    <div>
                        <span class="title">Saldo</span>
                        <h1 class="total">{{'RP'. $saldo ?  number_format($saldo->balance, 0, ',', '.') : '0'}}</h1>
                        <a href="#" class="badge badge-warning"> {{ $saldo ? number_format($saldo->points, 0, ',', '.').'Poin' : 0 . 'Poin'}} ></a>
                    </div>
                </div>
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

        <div class="section">
            <div class="row mt-2">
                <div class="col-12">
                    <div class="stat-box d-flex align-items-center justify-content-between" style="padding: 10px 15px;">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{asset('template-fe/assets/img/box.png')}}" alt="" style="max-width: 50px;">
                            <div class="mt-2">
                                <h2 class="title">Setor Sampah</h2>
                                <p style="font-size:10px;">Silahkan datang ke BSU</p>
                            </div>
                        </div>
                        <ion-icon name="arrow-forward-circle" style="width: 32px; height: 32px; color: #51D991;"></ion-icon>

                    </div>
                </div>
            </div>
        </div>

        <div class="section full mb-3">


            <!-- carousel full -->
            <div class="carousel-full splide splide--loop splide--ltr splide--draggable is-active" id="splide01"
                style="visibility: visible;">
                <div class="splide__track" id="splide01-track">
                    <ul class="splide__list" id="splide01-list" style="transform: translateX(-3120px);">

                        <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1"
                            style="width: 1040px;">
                            <div class="card rounded m-3 bg-dark">
                                <div class="card-body">
                                    <h5 class="card-title">Another Card Title</h5>
                                    <p class="card-text">
                                        Some quick example text to build on the card title and make up the bulk
                                        of the card's content.
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1"
                            style="width: 1040px;">
                            <div class="card rounded m-3 bg-dark">
                                <div class="card-body">
                                    <h5 class="card-title">Another Card Title</h5>
                                    <p class="card-text">
                                        Some quick example text to build on the card title and make up the bulk
                                        of the card's content.
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1"
                            style="width: 1040px;">
                            <div class="card rounded m-3 bg-dark">
                                <div class="card-body">
                                    <h5 class="card-title">Another Card Title</h5>
                                    <p class="card-text">
                                        Some quick example text to build on the card title and make up the bulk
                                        of the card's content.
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- * carousel full -->

        </div>
        <div class="section full mb-3">
            <div class="section-heading padding">
                <h2 class="title">Berita terbaru</h2>
                <a href="app-blog.html" class="link">Lihat semua</a>
            </div>

            <!-- carousel artikel -->
            @if ($articles->isEmpty())
            <div class="carousel-single splide splide--loop splide--ltr splide--draggable is-active" id="splide02"
                style="visibility: visible;">
                <div class="splide__track" id="splide02-track" style="padding-left: 16px; padding-right: 16px;">
                    <ul class="splide__list" id="splide02-list" style="transform: translateX(-1935.94px);">

                        @foreach ($articles as $article)
                <li class="splide__slide" style="margin-right: 16px; width: 325.333px;">
                    <div class="card">
                        <img src="{{ asset($article->image) }}" class="card-img-top" style=" height:auto;" alt="image">
                        <div class="card-body">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text">
                                {{ $article->content }}
                            </p>
                        </div>
                    </div>
                </li>
            @endforeach
                    </ul>
                </div>
            </div>
            @endif
            <div class="title p-3">Tunggu berita yang akan datang</div>
            <!-- * carousel artikel -->

        </div>



        <div class="section full mt-4 mb-3">
            <div class="section-heading padding">
                <h2 class="title">Rewards</h2>
                <a href="app-blog.html" class="link">Lihat semua</a>
            </div>

        
            <!-- carousel multiple -->
            <div class="carousel-multiple splide splide--loop splide--ltr splide--draggable is-active" id="splide03"
                style="visibility: visible;">
                <div class="splide__track" id="splide03-track" style="padding-left: 16px; padding-right: 16px;">
                    <ul class="splide__list" id="splide03-list" style="transform: translateX(-2224px);">
                        @foreach ($rewards as $reward)
                        
                        <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1"
                            style="margin-right: 16px; width: 240px;">
                            <a href="app-blog-post.html">
                                <div class="blog-card">
                                    <div class="image-wrapper d-flex justify-content-center">
                                        <img src="{{asset($reward->image)}}" alt="image" class="imaged w86">
                                    </div>
                                    <div class="text-wrapper p-2">
                                        <div class="mb-1 text-center">
                                            <h5>{{$reward->name}}</h5>
                                            <span class="badge badge-warning">{{number_format($reward->points, 0, ',', '.')}} Poin</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- * carousel multiple -->

        </div>

    </div>
    <!-- * App Capsule -->
@endsection
