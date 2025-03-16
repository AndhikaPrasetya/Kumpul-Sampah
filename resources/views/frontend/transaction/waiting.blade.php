@extends('layouts.layoutScreen')
@section('content')
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
       
        <!-- Konten -->
        <div class="p-6 grid gap-6 text-center">
            <div class="grid gap-2">
                <h1 class="text-2xl font-bold text-green-700">Terima Kasih!</h1>
                <p class="text-gray-600">Pesanan setor sampah Anda telah berhasil diterima.</p>
            </div>
            
         
            
            <div class="grid gap-2 bg-green-50 p-4 rounded-lg">
                <h2 class="font-semibold text-green-700">Langkah Selanjutnya:</h2>
                <p class="text-gray-700">Mohon tunggu admin melakukan pengecekan. </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-2">
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-gray-600 mt-2">Menunggu Verifikasi</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-gray-600 mt-2">Pesanan Diterima</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-gray-600 mt-2">Uang & Poin Ditambahkan</p>
                </div>
            </div>
            
            <div class="mt-2">
                <a href="{{route('home')}}" class="block w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 rounded-lg transition duration-300">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-50 p-4 border-t border-gray-100">
            <p class="text-xs text-center text-gray-500">
                Jika ada pertanyaan, silakan hubungi layanan pelanggan kami di 
                <a href="tel:+628123456789" class="text-green-600">0812-3456-789</a>
            </p>
        </div>
    </div>
@endsection