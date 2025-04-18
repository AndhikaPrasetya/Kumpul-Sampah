@extends('layouts.layoutMain',  ['noBottomMenu' => true])
@section('title', 'Transaksi Details')
@section('content')

<div class="max-w-sm md:max-w-xl mx-auto p-1">
    <!-- Card Header -->
    <div class="bg-white rounded-t-2xl p-5 shadow-sm">
        <h1 class="text-xl font-bold text-gray-800">Transaksi Details</h1>
        
        <!-- Transaction Info -->
        <div class="grid grid-cols-2 gap-3 mt-4">
            <p class="text-gray-600 text-sm">Tanggal</p>
            <p class="text-right font-medium text-gray-800 text-xs sm:text-sm">
                {{ \Carbon\Carbon::parse($transactionDate)->format('d M Y') }}
            </p>
            
            <p class="text-gray-600 text-sm">Kode Transaksi</p>
            <p class="text-right font-medium text-emerald-500 text-xs sm:text-sm">{{ $transactionCode }}</p>
        </div>
    </div>
    
    <!-- Item Details -->
    <div class="bg-white rounded-b-2xl p-5 shadow-sm">
        @foreach ($transactionDetail as $detail)
        <div class="grid grid-cols-4 items-center py-3 border-b border-gray-100">
            <div class="col-span-1">
                <div class="w-10 h-10 xs:w-12 xs:h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 rounded-md flex items-center justify-center overflow-hidden">
                    <img src="{{ asset($detail->sampah->image) }}" alt="image"
                        class="w-4/5 h-4/5 object-contain" />
                </div>
            </div>
            <div class="col-span-2">
                <p class="font-medium text-gray-800 text-sm">{{ $detail->sampah->nama }}</p>
                <p class="text-gray-500 text-xs">Rp {{ number_format($detail->sampah->harga, 0, ',', '.') }}/KG</p>
            </div>
            <div class="text-right">
                <p class="font-medium text-gray-800 text-xs sm:text-sm">{{ number_format($detail->berat, 0, ',', '.') }} KG</p>
                <p class="text-gray-500 text-xs">{{ number_format($detail->points, 0, ',', '.') }} Point</p>
            </div>
        </div>
        @endforeach

        <!-- Summary -->
        <div class="grid grid-cols-2 gap-3 mt-4 pt-2">
            <p class="text-gray-600 text-sm">Poin Diperoleh</p>
            <p class="text-right font-medium text-gray-500 text-xs sm:text-sm">
                {{ number_format($transaction->total_points, 0, ',', '.') }} Point
            </p>
            
            <p class="text-gray-600 font-medium text-sm">Total</p>
            <p class="text-right font-bold text-emerald-500 text-xs sm:text-sm">
                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Status Card -->
    <div class="bg-white rounded-2xl p-5 shadow-sm mt-4">
        <div class="grid grid-cols-2 gap-3 items-center">
            <p class="text-gray-600 text-sm">Status</p>
            <div class="text-right">
                @php
                $statusClasses = [
                    'approved' => 'bg-emerald-500 text-white',
                    'pending' => 'bg-yellow-500 text-white',
                    'rejected' => 'bg-red-500 text-white',
                ];
                @endphp

                <span class="{{ $statusClasses[$transaction->status] }} px-3 py-1 rounded-full text-sm font-medium">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>

            <p class="text-gray-600 text-sm">Tanggal Proses</p>
            <p class="text-right font-medium text-gray-800 text-xs sm:text-sm">
                {{ in_array($transaction->status, ["approved", "rejected"]) 
                    ? \Carbon\Carbon::parse($transaction->updated_at)->format('d M Y, H:i')
                    : '-' }}
            </p>
        </div>
    </div>
</div>

@endsection
