@extends('layouts.layoutSecond')
@section('title', 'Setor Sampah')
@section('content')


    <!-- Order Details -->

    <div class=" p-3 bg-white rounded-xl shadow-sm mb-4 overflow-hidden">
        <h2 class="text-base font-medium mb-4 ">Order Details</h2>

        <div class="flex justify-between mb-2">
            <span class="text-gray-600 text-sm">Tanggal</span>
            <span class="text-gray-800 text-sm">{{$transactionDate}}</span>
        </div>

        <div class="flex justify-between mb-4">
            <span class="text-gray-600 text-sm">Kode Transaksi</span>
            <span class="text-gray-800 text-green-600 text-sm">
                {{ $transactionCode }}
            </span>
        </div>
        
        <!-- Item List -->
        <div class="space-y-4">
            @foreach ($transactionDetail as $transactionDetail)
            <div class="flex items-center">
                <div class="w-10 h-10 xs:w-12 xs:h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 rounded-md flex items-center justify-center mr-3 bg-blue-100 overflow-hidden">
                    <img src="{{ asset('template-fe/assets/img/bottle.png') }}" alt="Bottle"
                        class="w-4/5 h-4/5 object-contain" />
                </div>
                <div class="flex-1">
                    <div class="flex justify-between">
                        <div>
                            <h3 class="font-medium text-sm">{{ $transactionDetail->sampah->nama }}</h3>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-sm">Qty: {{ number_format($transactionDetail->berat, 0, ',', '.') }} KG</p>
                            <p class="text-xs text-gray-500">{{ number_format($transactionDetail->points, 0, ',', '.') }} Point</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        

    </div>


@endsection
