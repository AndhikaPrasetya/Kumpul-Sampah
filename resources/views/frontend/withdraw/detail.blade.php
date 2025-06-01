@extends('layouts.layoutMain', ['noBottomMenu' => true])
@section('headTitle', 'Tarik Tunai Details')
@section('title', 'Tarik Tunai Details')
@section('content')

    <div class="max-w-sm md:max-w-xl mx-auto p-4">
        <!-- Card Header dengan Gradient -->
        <div class="bg-green-500 rounded-t-2xl p-6 text-white shadow-lg">
            <h1 class="text-2xl font-bold mb-2">Tarik Tunai Details</h1>
            <p class="text-sm opacity-90">Detail transaksi tarik tunai Anda</p>
        </div>

        <!-- Card Body -->
        <div class="bg-white rounded-b-2xl p-6 shadow-lg">
            <!-- Tanggal -->
         <!-- Tanggal -->
            <div class="flex justify-between items-center space-x-3 mb-4">
               
                    <p class="text-gray-600 text-sm">Kode Transaksi</p>
                    <p class="font-medium text-gray-800 text-sm">
                        {{ $withdraw->transaction_code }}
                    </p>
              
            </div>
            <div class="flex justify-between items-center space-x-3 mb-4">
                <p class="text-gray-600 text-sm">Tanggal</p>
                <p class="font-medium text-gray-800 text-sm">
                    {{ \Carbon\Carbon::parse($withdraw->created_at)->format('d M Y, H:i') }}
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
                    <p class="text-gray-600 text-sm">Nominal</p>
                    <p class="font-bold text-emerald-500 text-sm">
                        Rp {{ number_format($withdraw->amount, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-3 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                    <path fill-rule="evenodd"
                        d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-gray-600 text-sm">Metode Penarikan</p>
                    <p class="text-sm font-medium">
                        {{ $withdraw->metode_penarikan }}
                    </p>
                </div>
            </div>

            <!-- Status -->
            <div class="flex items-center space-x-3 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-gray-600 text-sm">Status</p>

                    <span class="text-sm font-medium">
                        {{ ucfirst($withdraw->status) }}
                    </span>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-gray-600 text-sm">Bukti Pencairan Dana</p>

                    <div class="text-sm">
                        <a href="{{ asset('storage/' . $withdraw->image) }}"
                            download="bukti-transfer-{{ $withdraw->transaction_code }}.jpg"
                            class="inline-flex items-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Lihat & Download Bukti
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tanggal Proses -->
            <div class=" mt-4 pt-4 border-t border-gray-100">
                <div class="tanggal-proses">
                    <p class="text-gray-600 text-sm">Tanggal Proses</p>
                    <p class="font-medium text-gray-800 text-sm">
                        {{ in_array($withdraw->status, ['approved', 'rejected'])
                            ? \Carbon\Carbon::parse($withdraw->updated_at)->format('d M Y, H:i')
                            : '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection
