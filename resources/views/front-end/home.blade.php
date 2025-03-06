@extends('layouts.layout-fe')
@section('content')
  <!-- App Capsule -->
  <div id="appCapsule">

    <!-- Wallet Card -->
    @php
    use Carbon\Carbon;

    $hour = Carbon::now()->hour;
    if ($hour < 12) {
        $greeting = "Selamat Pagi";
    } elseif ($hour < 18) {
        $greeting = "Selamat Sore";
    } else {
        $greeting = "Selamat Malam!";
    }
@endphp
    <div class="section wallet-card-section pt-1">
        <div class="profile-wrapper d-flex gap-3">
            <div class="name-wrapper">
                <span  style="color:#ffffff; font-size:18px; font-weight:bold;">{{$greeting}}, <span>Andhika.</span> 👋 </span>
                <p style="color:#ffffff; font-size:18px; font-weight:bold;">Ayo Kumpulkan Sampah dan Setorkan!</p>
            </div>
            
        </div>
        <div class="wallet-card ">
            <!-- Balance -->
            <div class="balance">
                <div>
                    <span class="title">Saldo</span>
                    <h1 class="total">Rp 50.000</h1>
                    <span class="badge badge-warning"> 200 Points ></span>
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
                        <strong>Withdraw</strong>
                    </a>
                </div>
                <div class="item">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#sendActionSheet">
                        <div class="icon-wrapper">
                            <ion-icon name="sync"></ion-icon>
                        </div>
                        <strong>Redeem Point</strong>
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

    <div class="section full mt-2 mb-3">


        <!-- carousel full -->
        <div class="carousel-full splide splide--loop splide--ltr splide--draggable is-active" id="splide01" style="visibility: visible;">
            <div class="splide__track" id="splide01-track">
                <ul class="splide__list" id="splide01-list" style="transform: translateX(-3120px);">

                    <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="width: 1040px;">
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
                    <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="width: 1040px;">
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
                    <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="width: 1040px;">
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
        <div class="section-title">Rekomendasi</div>

        <!-- carousel single -->
        <div class="carousel-single splide splide--loop splide--ltr splide--draggable is-active" id="splide02" style="visibility: visible;">
            <div class="splide__track" id="splide02-track" style="padding-left: 16px; padding-right: 16px;">
                <ul class="splide__list" id="splide02-list" style="transform: translateX(-1935.94px);">

                    <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 325.333px;">
                        <div class="card">
                            <img src="{{asset('template-fe/assets/img/sample/photo/wide2.jpg')}}" class="card-img-top" alt="image">
                            <div class="card-body">
                                <h5 class="card-title">Carousel Item</h5>
                                <p class="card-text">
                                    Some quick example text to build on the card title and make up the bulk
                                    of the card's content.
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 325.333px;">
                        <div class="card">
                            <img src="{{asset('template-fe/assets/img/sample/photo/wide2.jpg')}}" class="card-img-top" alt="image">
                            <div class="card-body">
                                <h5 class="card-title">Carousel Item</h5>
                                <p class="card-text">
                                    Some quick example text to build on the card title and make up the bulk
                                    of the card's content.
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 325.333px;">
                        <div class="card">
                            <img src="{{asset('template-fe/assets/img/sample/photo/wide2.jpg')}}" class="card-img-top" alt="image">
                            <div class="card-body">
                                <h5 class="card-title">Carousel Item</h5>
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
        <!-- * carousel single -->

    </div>



    <div class="section full mt-4 mb-3">
        <div class="section-heading padding">
            <h2 class="title">Berita terbaru</h2>
            <a href="app-blog.html" class="link">View All</a>
        </div>

        <!-- carousel multiple -->
        <div class="carousel-multiple splide splide--loop splide--ltr splide--draggable is-active" id="splide03" style="visibility: visible;">
            <div class="splide__track" id="splide03-track" style="padding-left: 16px; padding-right: 16px;">
                <ul class="splide__list" id="splide03-list" style="transform: translateX(-2224px);">

                    <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/1.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">What will be the value of bitcoin in the next...</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/2.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">Rules you need to know in business</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/3.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">10 easy ways to save your money</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/4.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">Follow the financial agenda with...</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide splide__slide--clone" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/1.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">What will be the value of bitcoin in the next...</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide splide__slide--clone" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/2.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">Rules you need to know in business</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide splide__slide--clone" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/3.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">10 easy ways to save your money</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide splide__slide--clone" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/4.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">Follow the financial agenda with...</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide" id="splide03-slide01" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/1.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">What will be the value of bitcoin in the next...</h4>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li class="splide__slide is-active is-visible" id="splide03-slide02" aria-hidden="false" tabindex="0" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/2.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">Rules you need to know in business</h4>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li class="splide__slide is-visible" id="splide03-slide03" aria-hidden="false" tabindex="0" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/3.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">10 easy ways to save your money</h4>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li class="splide__slide is-visible" id="splide03-slide04" aria-hidden="false" tabindex="0" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/4.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">Follow the financial agenda with...</h4>
                                </div>
                            </div>
                        </a>
                    </li>

                <li class="splide__slide splide__slide--clone is-visible" aria-hidden="false" tabindex="0" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/1.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">What will be the value of bitcoin in the next...</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/2.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">Rules you need to know in business</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/3.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">10 easy ways to save your money</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/4.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">Follow the financial agenda with...</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide splide__slide--clone" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/1.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">What will be the value of bitcoin in the next...</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide splide__slide--clone" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/2.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">Rules you need to know in business</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide splide__slide--clone" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/3.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">10 easy ways to save your money</h4>
                                </div>
                            </div>
                        </a>
                    </li><li class="splide__slide splide__slide--clone" style="margin-right: 16px; width: 240px;">
                        <a href="app-blog-post.html">
                            <div class="blog-card">
                                <img src="{{asset('template-fe/assets/img/sample/photo/4.jpg')}}" alt="image" class="imaged w-100">
                                <div class="text">
                                    <h4 class="title">Follow the financial agenda with...</h4>
                                </div>
                            </div>
                        </a>
                    </li></ul>
            </div>
        </div>
        <!-- * carousel multiple -->

    </div>

</div>
<!-- * App Capsule -->    
@endsection
