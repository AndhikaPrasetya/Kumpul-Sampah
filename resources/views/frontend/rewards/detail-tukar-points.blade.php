@extends('layouts.layoutMain', ['noBottomMenu' => true])
@section('headTitle', 'Tukar Points Details')
@section('title', 'Tukar Points Details')
@section('content')

    <div class="max-w-sm md:max-w-xl mx-auto p-4">
        <!-- Card Header dengan Gradient -->
        <div class="bg-green-500 rounded-t-2xl p-6 text-white shadow-lg">
            <h1 class="text-2xl font-bold mb-2">Tukar Points Details</h1>
            <p class="text-sm opacity-90">Detail transaksi Tukar Points Anda</p>
        </div>

        <!-- Card Body -->
        <div class="bg-white rounded-b-2xl p-6 shadow-lg">
            <!-- Tanggal -->
            <div class="flex justify-between items-center space-x-3 mb-4">
               
                    <p class="text-gray-600 text-sm">Kode Transaksi</p>
                    <p class="font-medium text-gray-800 text-sm">
                        {{ $tukarPoints->transaction_code }}
                    </p>
              
            </div>
            <div class="flex justify-between items-center space-x-3 mb-4">
                <p class="text-gray-600 text-sm">Tanggal</p>
                <p class="font-medium text-gray-800 text-sm">
                    {{ \Carbon\Carbon::parse($tukarPoints->created_at)->format('d M Y, H:i') }}
                </p>
            </div>

            <!-- Total -->
            <div class="flex items-center space-x-3 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                    <path fill-rule="evenodd"
                        d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-gray-600 text-sm">Point yang ditukarkan</p>
                    <p class="font-bold text-emerald-500 text-sm">
                        {{ number_format($tukarPoints->total_points, 0, ',', '.') }} Points
                    </p>
                </div>
            </div>

            <!-- Status -->
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-gray-600 text-sm">Status</p>

                    <span class="text-sm font-medium">
                        {{ ucfirst($tukarPoints->status) }}
                    </span>
                </div>
            </div>

            <!-- Setelah section Status, tambahkan ini -->
            <!-- Reward yang Ditukar -->
            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-start space-x-3 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mt-0.5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-gray-600 text-sm mb-2">Reward yang Ditukar</p>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <!-- Image dan Content Container -->
                            <div class="flex items-start space-x-3">
                                <!-- Image Reward -->
                                @if ($tukarPoints->reward->image ?? false)
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset($tukarPoints->reward->image) }}"
                                            alt="{{ $tukarPoints->reward->name }}"
                                            class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                                    </div>
                                @else
                                    <!-- Default Image jika tidak ada gambar -->
                                    <div
                                        class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif

                                <!-- Text Content -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-800">
                                        {{ $tukarPoints->reward->name ?? 'Reward tidak ditemukan' }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $tukarPoints->description ?? '' }}</p>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                            {{ number_format($tukarPoints->reward->points ?? 0, 0, ',', '.') }} Points
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tanggal Proses -->
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-gray-600 text-sm">Tanggal Proses</p>
                <p class="font-medium text-gray-800 text-sm">
                    {{ in_array($tukarPoints->status, ['approved', 'rejected'])
                        ? \Carbon\Carbon::parse($tukarPoints->updated_at)->format('d M Y, H:i')
                        : '-' }}
                </p>
            </div>
        </div>
    </div>

@endsection
