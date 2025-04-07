@extends('layouts.layoutMain', ['noHeader' => true])
@section('headTitle', 'Bang Imam')
@section('content')
    <div class="p-2">
        <!-- Greeting Section -->
        <div class="relative">
            <!-- Improved Scroll Header -->
            <div id="scroll-header"
                class="fixed top-0 left-0 right-0 z-40 max-w-screen-sm mx-auto bg-gradient-to-r from-green-500 to-emerald-600 shadow-md transition-all duration-300 rounded-b-2xl">
                <!-- Profile Section (Always Visible) -->
                <div class="p-4" id="profile-section">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-white line-clamp-1">
                                {{ Auth::user()->name }}
                            </h2>
                            <p class="text-white/90 text-xs mt-1 flex items-center">
                                <span class="w-2 h-2 bg-green-300 rounded-full mr-2"></span>
                                Ayo kumpulkan sampah dan setorkan!
                            </p>
                        </div>
                     
                    </div>
                </div>

                <!-- Collapsible Section -->
                <div id="collapsible-section" class="transition-all duration-300 overflow-hidden">
                    <!-- Balance Cards -->
                    <div class="px-4 pb-2">
                        @livewire('saldo-info')

                    </div>

                    <!-- Quick Actions -->
                    <div class="px-2 pb-3">
                        <div class="grid grid-cols-3 gap-1">
                            <!-- Tarik Tunai -->
                            <a href="{{ route('tarik-tunai') }}"
                                class="flex flex-col items-center p-2 rounded-lg hover:bg-white/5 transition">
                                <div class="bg-white/20 p-2 rounded-full mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <span class="text-xs text-white font-medium">Tarik Tunai</span>
                            </a>

                            <!-- Tukar Poin -->
                            <a href="{{ route('listRewards') }}"
                                class="flex flex-col items-center p-2 rounded-lg hover:bg-white/5 transition">
                                <div class="bg-white/20 p-2 rounded-full mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-xs text-white font-medium">Tukar Poin</span>
                            </a>

                            <!-- Riwayat -->
                            <a href="{{ route('transaksiFrontend.index') }}"
                                class="flex flex-col items-center p-2 rounded-lg hover:bg-white/5 transition">
                                <div class="bg-white/20 p-2 rounded-full mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-xs text-white font-medium">Riwayat</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Spacer -->
            <div id="header-spacer" class="transition-all duration-300"></div>
        </div>

        <!-- Setor Sampah Section -->
        <a href="{{ route('setor-sampah') }}" class="block mb-4">
            <div
                class="mt-4 bg-white rounded-2xl shadow-md overflow-hidden flex items-center p-4 hover:shadow-lg transition-all transform hover:scale-[1.01]">
                <div class="bg-green-100 p-3 rounded-xl mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor">
                        <!-- Recycle Bin Outline -->
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 6h18m-2 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />


                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Setor Sampah</h3>
                    <p class="text-sm text-gray-500">Timbang sampah Anda untuk mengetahui jumlah yang dapat ditukar</p>
                </div>
                <div class="ml-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>
            </div>
        </a>

        <section>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Kategori Sampah</h2>
                <a href="{{ route('sampahlist') }}"
                    class="text-green-600 hover:text-green-700 transition text-sm font-medium">
                    Lihat Semua
                </a>
            </div>
            @php
            $icons = [
                'Plastik' => 'fa-recycle',
                'Bodong A' => 'fa-trash-alt',
                'Tutup Botol' => 'fa-wine-bottle',
                'Tutup Galon' => 'fa-prescription-bottle',
                'Ember Warna' => 'fa-box',
                'Ember Hitam' => 'fa-box',
                'Paralon' => 'fa-water',
                'Naso' => 'fa-question-circle',
                'Kresek' => 'fa-shopping-bag',
                'Galon Aqua' => 'fa-tint',
                'Akrilik' => 'fa-layer-group',
                'Gelas Kotor' => 'fa-glass-whiskey',
                'Inject' => 'fa-syringe',
                'Mainan' => 'fa-puzzle-piece',
            ];
        @endphp
            @if ($categorySampah->isEmpty())
                <div class="bg-white rounded-2xl p-6 text-center shadow-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                    <p class="text-gray-600">Belum ada kategori sampah yang tersedia</p>
                </div>
            @else
                <div class="swiper sampahSwiper">
                    <div class="swiper-wrapper">
                        @foreach ($categorySampah as $item)
                        <div class="swiper-slide ">
                            <a href="{{route('detailKategori',$item->nama)}}">

                                <div
                                    class="bg-white rounded-2xl w-28 shadow-md p-4 mb-4 flex flex-col items-center relative overflow-hidden">
    
                                    <!-- Content Container -->
                                    <div class="flex flex-col items-center justify-center">
                                        <!-- Image with Hover Effect -->
                                        <div class="mb-3 transform transition-transform duration-300 hover:scale-110">
                                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                                                <i class="fas {{ $icons[$item->nama] ?? 'fa-trash' }} text-xl"></i>
                                            </div>
                                        </div>
    
                                        <!-- Title -->
                                        <p class="text-xs font-bold text-gray-800 mb-2">{{$item->nama}}</p>
    
                                    </div>
                                </div>
                            </a>
                        </div>
                            
                        @endforeach


                    </div>
                </div>
            @endif
        </section>

        <!-- Transaction List -->
        @livewire('transaction-list')

        <!-- Latest News -->
        <section class="mb-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Berita Terbaru</h2>
                <a href="{{ route('listBlog') }}" class="text-green-600 hover:text-green-700 transition text-sm font-medium">
                    Lihat Semua
                </a>
            </div>
        
            @if ($articles->isNotEmpty())
                    <!-- Swiper container -->
                    <div class="swiper newsSwiper">
                        <div class="swiper-wrapper pb-8">
                            @foreach ($articles as $article)
                            <div class="swiper-slide">
                                <a href="{{ route('detailBlog', $article->slug) }}" class="block h-full">
                                    <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all h-full flex flex-col">
                                        <!-- Thumbnail with aspect ratio -->
                                        <div class="aspect-w-16 aspect-h-9 overflow-hidden">
                                            <img src="{{ asset($article->thumbnail) }}" alt="{{ $article->title }}" 
                                                class="w-full h-52 object-cover"
                                                onerror="this.src='{{ asset('path/to/default-news-image.jpg') }}'">
                                        </div>
                                        
                                        <div class="p-4 flex-grow flex flex-col">
                                            <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">{{ $article->title }}</h3>
                                            <p class="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow">
                                                {{ Str::limit(strip_tags($article->content), 50) }}
                                            </p>
                                            <div class="flex justify-between items-center mt-auto">
                                                <span class="text-sm text-gray-500">{{ $article->created_at->format('d M Y') }}</span>
                                                <span class="text-green-600 hover:text-green-700 font-medium text-sm">
                                                    Baca Selengkapnya &rarr;
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                
            @else
                <div class="bg-white rounded-2xl p-6 text-center shadow-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <p class="text-gray-600">Tunggu berita yang akan datang</p>
                </div>
            @endif
        </section>
        <!-- Rewards Section -->
        <section class="pb-20">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Rewards</h2>
                <a href="{{ route('listRewards') }}"
                    class="text-green-600 hover:text-green-700 transition text-sm font-medium">
                    Lihat Semua
                </a>
            </div>

            @if ($rewards->isEmpty())
                <div class="bg-white rounded-2xl p-6 text-center shadow-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                    <p class="text-gray-600">Belum ada rewards yang tersedia</p>
                </div>
            @else
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach ($rewards as $reward)
                            @php
                                $progress = min(100, ($currentPoints / $reward->points) * 100);
                            @endphp
                            <div class="swiper-slide border-black">
                                <a href="{{ route('detailReward', $reward->id) }}">
                                    <div class="bg-white rounded-2xl">
                                        <div class="p-4">
                                            <div class="mb-2">
                                                <img src="{{ asset($reward->image) }}" alt="{{ $reward->name }}"
                                                    class="w-full h-[150px] object-cover bg-gray-100">
                                            </div>
                                            <h3 class="text-base font-bold text-gray-800 mb-2 line-clamp-2">
                                                {{ $reward->name }}</h3>
                                            <div class="flex justify-between items-center mb-2">
                                                <div class="flex items-center">
                                                    <i class="fas fa-coins text-yellow-500 me-1"></i>
                                                    <span
                                                        class="text-sm font-bold text-gray-700">{{ number_format($reward->points, 0, ',', '.') }}
                                                        Points</span>
                                                </div>
                                                <span class="text-sm text-green-600 font-medium">Stok:
                                                    {{ $reward->stok }}</span>
                                            </div>
                                            <div class="mb-3">
                                                <div class="relative flex bg-gray-200 rounded-full h-4 mb-2">
                                                    <div class="bg-green-500 h-4 rounded-full flex items-center justify-end pr-2 transition-all duration-500 ease-out"
                                                        style="width: {{ $progress }}%; min-width: 2rem;">
                                                        <span class="text-white text-xs font-bold">
                                                            {{ round($progress) }}%
                                                        </span>
                                                    </div>
                                                    @if ($progress < 10)
                                                        <span
                                                            class="absolute text-xs text-gray-600 font-bold left-2 top-1/2 transform -translate-y-1/2">
                                                            {{ round($progress) }}%
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <a href="{{ route('listRewards') }}" class="block w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium text-sm sm:text-base py-1.5 sm:py-2 px-3 sm:px-4 rounded-lg transition-colors duration-300 @if($reward->stok <= 0) opacity-50 cursor-not-allowed @endif">
                                                @if($reward->stok <= 0)
                                                    Stok habis
                                                @else
                                                   Masih Tersedia
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>

    </div>

    <style>
        #collapsible-section {
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            const $header = $('#scroll-header');
            const $collapsible = $('#collapsible-section');
            const $spacer = $('#header-spacer');
            const $profileSection = $('#profile-section');

            // Calculate heights
            const fullHeight = $header.outerHeight();
            const collapsedHeight = $profileSection.outerHeight() + 16; // + padding

            // Set initial spacer height
            $spacer.height(fullHeight);

            let lastScroll = 0;
            const scrollThreshold = 10;

            $(window).scroll(function() {
                const currentScroll = $(this).scrollTop();

                if (currentScroll > scrollThreshold) {
                    // Scrolling down - collapse
                    if (currentScroll > lastScroll) {
                        $header.addClass('shadow-2xl');
                        $collapsible.css({
                            'max-height': '0',
                            'opacity': '0'
                        });
                        $spacer.height(collapsedHeight);
                    }
                    // Scrolling up - expand
                    else if (currentScroll < (lastScroll - 15)) {
                        $collapsible.css({
                            'max-height': '500px',
                            'opacity': '1'
                        });
                        $spacer.height(fullHeight);
                    }
                } else {
                    // At top - fully expanded
                    $header.removeClass('shadow-2xl');
                    $collapsible.css({
                        'max-height': '500px',
                        'opacity': '1'
                    });
                    $spacer.height(fullHeight);
                }

                lastScroll = currentScroll;
            });
        });

        const swiper = new Swiper(".mySwiper", {
            slidesPerView: 3,
            spaceBetween: 30,
            freeMode: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1, // 2 item di layar kecil
                },
                425: {
                    slidesPerView: 2, // 2 item di layar kecil
                },
                768: {
                    slidesPerView: 3, // 3 item di tablet
                },
                1024: {
                    slidesPerView: 2, // 4 item di layar besar
                },
            },
        });
        const newsSwiper = new Swiper(".newsSwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
          
        });

        const sampahSwiper = new Swiper(".sampahSwiper", {
            slidesPerView: 5,
            freeMode: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                320: {
                    slidesPerView: 3,
                    spaceBetween: 60,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 1, // 4 item di layar besar
                },
            },
        });
    </script>
@endsection
