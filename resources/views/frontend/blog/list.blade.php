@extends('layouts.layoutMain', ['noBottomMenu' => false])
@section('headTitle', 'Berita')
@section('title', 'Berita')
@section('content')
{{-- Swiper Container --}}
<div class="w-full max-w-2xl mx-auto mb-6">
    @if ($heroNews && count($heroNews) > 0)
    <div class="swiper mySwiper rounded-2xl overflow-hidden shadow-lg">
        <div class="swiper-wrapper">
            @foreach ($heroNews as $news)
            <div class="swiper-slide">
                <a href="{{ route('detailBlog', $news->slug) }}" class="block h-full">
                    {{-- Gambar Latar Belakang --}}
                    <div class="relative pb-[56.25%]"> {{-- Aspek rasio 16:9 --}}
                        <img src="{{ asset($news->thumbnail) }}" alt="{{ $news->title }}" class="absolute h-full w-full object-cover"
                            onerror="this.src='{{ asset('images/default-hero-news.jpg') }}'"> {{-- Pastikan path gambar default benar --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div> {{-- Overlay gradasi --}}
                    </div>

                    {{-- Konten Teks Overlay --}}
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h2 class="text-2xl sm:text-3xl font-extrabold leading-tight mb-2 line-clamp-2">
                            {{ $news->title }}
                        </h2>
                        <div class="flex items-center space-x-3 text-sm text-gray-200">
                            <span class="flex items-center bg-white/20 rounded-full px-3 py-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ rand(3, 7) }} min read {{-- Contoh waktu baca acak --}}
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        {{-- Add Pagination --}}
        <div class="swiper-pagination absolute bottom-4 left-0 right-0 flex justify-center space-x-2 z-10"></div>
    </div>
    @endif
</div>

<div class="px-4 sm:px-6 lg:px-8 mx-auto max-w-2xl min-h-screen">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Article Terpopuler</h3>
    {{-- Grid 2 kolom pada mobile --}}
    <div class="grid grid-cols-2 gap-4 sm:gap-6">
        @foreach ($otherNews as $news)
        <a href="{{ route('detailBlog', $news->slug) }}" class="block bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 h-full flex flex-col transform hover:-translate-y-1 group">
            {{-- Gambar Berita --}}
            <div class="aspect-[16/9] overflow-hidden rounded-t-xl">
                <img src="{{ asset($news->thumbnail) }}" 
                     alt="{{ $news->title }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                     onerror="this.src='{{ asset('images/default-other-news.jpg') }}'">
            </div>

            {{-- Konten Teks --}}
            <div class="p-3 flex-grow flex flex-col">
                <h3 class="text-sm font-semibold text-gray-800 mb-1 line-clamp-2">
                    {{ $news->title }}
                </h3>
                <p class="text-gray-600 text-xs mb-2 line-clamp-3 flex-grow">
                    {{ Str::limit(strip_tags($news->content), 50) }}
                </p>
                <div class="flex justify-between items-center text-xs text-gray-500 mt-auto pt-2 border-t border-gray-50">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ rand(2, 5) }} min read
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    
    {{-- Pagination --}}
    @if ($otherNews->hasPages())
    <div class="mt-8 flex justify-center">
        <nav class="flex items-center space-x-2">
            {{-- Previous Button --}}
            @if ($otherNews->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $otherNews->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-800 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($otherNews->getUrlRange(1, $otherNews->lastPage()) as $page => $url)
                @if ($page == $otherNews->currentPage())
                    <span class="px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-800 transition-colors duration-200">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Button --}}
            @if ($otherNews->hasMorePages())
                <a href="{{ $otherNews->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-800 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </nav>
    </div>
    @endif
</div>

@endsection
@section('script')
<script>
    // Inisialisasi Swiper
    document.addEventListener('DOMContentLoaded', function() {
        try {
            // Periksa apakah elemen Swiper ada sebelum inisialisasi
            const swiperContainer = document.querySelector('.mySwiper');
            if (!swiperContainer) {
                console.warn('Swiper container (.mySwiper) not found. Swiper will not be initialized.');
                return;
            }

            const swiper = new Swiper('.mySwiper', {
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    renderBullet: function (index, className) {
                        return '<span class="' + className + ' !w-2 !h-2 !mx-1 !bg-white !opacity-50 !rounded-full transition-opacity duration-300"></span>';
                    },
                },
                speed: 800,
                spaceBetween: 0,
                loop: true, // Tambahkan loop untuk pengalaman yang lebih baik
                autoplay: {
                    delay: 5000, // Auto slide setiap 5 detik
                    disableOnInteraction: false,
                },
            });

            // Menyesuaikan bullet aktif
            swiper.on('paginationUpdate', function (swiper, paginationEl) {
                const bullets = paginationEl.querySelectorAll('.swiper-pagination-bullet');
                bullets.forEach((bullet, index) => {
                    if (index === swiper.realIndex) {
                        bullet.classList.remove('opacity-50');
                        bullet.classList.add('opacity-100');
                    } else {
                        bullet.classList.remove('opacity-100');
                        bullet.classList.add('opacity-50');
                    }
                });
            });
            
            console.log('Swiper successfully initialized.');
        } catch (error) {
            console.error('Swiper initialization error:', error);
        }
    });
</script>
@endsection