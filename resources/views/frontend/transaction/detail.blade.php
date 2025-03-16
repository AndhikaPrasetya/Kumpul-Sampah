@extends('layouts.layoutSecond')
@section('title', 'Transaksi Details')
@section('content')

    <div class=" max-w-sm md:max-w-xl mx-auto p-1">
        <!-- Card Header -->
        <div class="bg-white rounded-t-2xl p-5 shadow-sm">
            <h1 class="text-xl font-bold text-gray-800">Transaksi Details</h1>
            
            <!-- Transaction Info -->
            <div class="grid grid-cols-2 gap-3 mt-4">
                <p class="text-gray-600 text-sm">Tanggal</p>
                <p class="text-right font-medium text-gray-800 text-xs sm:text-sm">{{$transactionDate}}</p>
                
                <p class="text-gray-600 text-sm ">Kode Transaksi</p>
                <p class="text-right font-medium text-emerald-500 text-xs sm:text-sm">{{ $transactionCode }}</p>
            </div>
        </div>
        
        <!-- Item Details - Simplified Layout -->
        <div class="bg-white rounded-b-2xl p-5 shadow-sm mt-1">
            <!-- Item Row -->
            @foreach ($transactionDetail as $transactionDetail)
            <div class="grid grid-cols-4 items-center py-3 border-b border-gray-100">
                <div class="col-span-1">
                    <div class="w-10 h-10 xs:w-12 xs:h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 rounded-md flex items-center justify-center mr-3 overflow-hidden">
                        <img src="{{ asset($transactionDetail->sampah->image) }}" alt="image"
                            class="w-4/5 h-4/5 object-contain" />
                    </div>
                </div>
                <div class="col-span-2">
                    <p class="font-medium text-gray-800 text-sm">{{ $transactionDetail->sampah->nama }}</p>
                    <p class="text-gray-500 text-xs">Rp {{ number_format($transactionDetail->sampah->harga, 0, ',', '.') }}/KG</p>
                </div>
                <div class="text-right">
                    <p class="font-medium text-gray-800 text-xs sm:text-sm">{{ number_format($transactionDetail->berat, 0, ',', '.') }} KG</p>
                    <p class="text-gray-500 text-xs">{{ number_format($transactionDetail->points, 0, ',', '.') }} Point</p>
                </div>
            </div>
            <!-- Summary -->
            @endforeach
            <div class="grid grid-cols-2 gap-3 mt-4 pt-2">
                
                <p class="text-gray-600 text-sm">Poin Diperoleh</p>
                <p class="text-right font-medium text-gray-500 text-xs sm:text-sm"> {{ number_format($transaction->total_points, 0, ',', '.') }} Point</p>
                
                <p class="text-gray-600 font-medium text-sm">Total</p>
                <p class="text-right font-bold text-emerald-500 text-xs sm:text-sm">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <!-- Status Card -->
        <div class="bg-white rounded-2xl p-5 shadow-sm mt-4">
            <div class="grid grid-cols-2 gap-3 items-center">
                <p class="text-gray-600 text-sm">Status</p>
                <div class="text-right">
                    @if ($transaction->status === "approved")
                    <span class="bg-emerald-500 text-white px-3 py-1 rounded-full text-sm font-medium">Approved</span>
                    @elseif($transaction->status === "pending")
                    <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-medium">Pending</span>
                    @elseif($transaction->status === "rejected")
                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">Rejected</span>
                    @endif
                </div>
                
                <p class="text-gray-600 text-sm">Tanggal Proses</p>
                @if (in_array($transaction->status, ["approved", "rejected"]))
                <p class="text-right font-medium text-gray-800 text-xs sm:text-sm">{{ $transaction->updated_at }}</p>
            @else
                <p class="text-right font-medium text-gray-800">-</p>
            @endif
            </div>
        </div>
        
     
    </div>
@endsection
