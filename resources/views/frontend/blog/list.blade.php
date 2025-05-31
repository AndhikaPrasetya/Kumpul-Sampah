@extends('layouts.layoutMain', ['noBottomMenu' => false])
@section('headTitle', 'Berita')
@section('title', 'Berita')
@section('content')
{{-- Swiper Container --}}
<div class="relative w-full max-w-2xl mx-auto mb-6">
    {{-- Pastikan $heroNews adalah koleksi/array jika Anda ingin carousel berfungsi dengan banyak slide --}}
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

<div class="px-4 sm:px-6 lg:px-8 mx-auto max-w-2xl">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Popular Article</h3>
    {{-- Perubahan di sini untuk grid 2 kolom pada mobile --}}
    <div class="grid grid-cols-2 gap-4 sm:gap-6">
        @foreach ($otherNews as $news)
        <a href="{{ route('detailBlog', $news->slug) }}" class="block bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 h-full flex flex-col transform hover:-translate-y-1 group"> {{-- Menambahkan group untuk hover pada gambar --}}
            {{-- Gambar Berita --}}
            <div class="relative pb-[56.25%] overflow-hidden rounded-t-xl"> {{-- Aspek rasio 16:9 --}}
                <img src="{{ asset($news->thumbnail) }}" alt="{{ $news->title }}" class="absolute h-full w-full object-cover group-hover:scale-105 transition-transform duration-300" {{-- Efek zoom pada gambar saat hover --}}
                    onerror="this.src='{{ asset('images/default-other-news.jpg') }}'"> {{-- Pastikan path gambar default benar --}}
                {{-- Kategori Overlay --}}
            </div>

            {{-- Konten Teks --}}
            <div class="p-3 flex-grow flex flex-col"> {{-- Penyesuaian padding untuk mobile --}}
                <h3 class="text-sm font-semibold text-gray-800 mb-1 line-clamp-2"> {{-- Penyesuaian ukuran font dan margin untuk mobile --}}
                    {{ $news->title }}
                </h3>
                <p class="text-gray-600 text-xs mb-2 line-clamp-3 flex-grow"> {{-- Penyesuaian ukuran font dan margin untuk mobile --}}
                    {{ Str::limit(strip_tags($news->content), 50) }} {{-- Penyesuaian limit karakter untuk mobile --}}
                </p>
                <div class="flex justify-between items-center text-xs text-gray-500 mt-auto pt-2 border-t border-gray-50">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> {{-- Penyesuaian ukuran ikon untuk mobile --}}
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ rand(2, 5) }} min read {{-- Penyesuaian rentang waktu baca untuk mobile --}}
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>


@endsection
@section('script')
<script>
    // Inisialisasi Swiper
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.mySwiper', {
            loop: true, // Untuk looping carousel
            autoplay: {
                delay: 5000, // Ganti slide setiap 5 detik
                disableOnInteraction: false, // Lanjutkan autoplay setelah interaksi pengguna
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                // Custom bullet styling for Tailwind CSS
                renderBullet: function (index, className) {
                    return '<span class="' + className + ' !w-2 !h-2 !mx-1 !bg-white !opacity-50 !rounded-full transition-opacity duration-300"></span>';
                },
            },
            // Optional parameters
            speed: 800, // Kecepatan transisi slide
            spaceBetween: 0, // Jarak antar slide
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
    });
</script>
@endsection
