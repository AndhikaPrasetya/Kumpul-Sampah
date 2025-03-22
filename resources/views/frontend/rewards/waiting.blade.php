@extends('layouts.layoutMain', ['noBottomMenu' => true, 'noHeader' => true])
@section('headTitle', 'Penukaran Rewards Berhasil')
@section('content')
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
       
        <!-- Konten -->
        <div class="p-6 grid gap-6 text-center">
            <div class="grid gap-2">
                <h1 class="text-2xl font-bold text-green-700">Penukaran Rewards Berhasil!</h1>
                <p class="text-gray-600">Reward Anda telah berhasil ditukar dan siap diambil di BSU (Bank Sampah Unit) terdekat.</p>
            </div>
            
            <div class="grid gap-2 bg-yellow-50 p-4 rounded-lg">
                <h2 class="font-semibold text-yellow-700">Langkah Selanjutnya:</h2>
                <p class="text-gray-700">Silakan kunjungi BSU daerah anda untuk mengambil reward Anda. Jangan lupa membawa bukti penukaran.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-2">
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-gray-600 mt-2">Tukar Reward</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-sm text-gray-600 mt-2">Kunjungi BSU</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-sm text-gray-600 mt-2">Reward Diterima</p>
                </div>
            </div>
            
            <div class="mt-2">
                <a href="{{ route('home') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 rounded-lg transition duration-300">
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