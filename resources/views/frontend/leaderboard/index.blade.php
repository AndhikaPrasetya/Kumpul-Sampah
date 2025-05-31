@extends('layouts.layoutMain')
@section('headTitle', 'Peringkat')
@section('title', 'Peringkat')
@section('content')
<div class="pb-24">

    <div class="text-center my-4">
        <h1 class="text-xl font-bold text-gray-800">Pahlawan Lingkungan</h1>
    </div>
    
    
    <!-- Card Container -->
    <div class="bg-white rounded-b-lg shadow mx-4 overflow-hidden">
        
        <!-- Info Panel -->
        <div class="px-4 py-4 bg-gray-50 border-t border-b border-gray-200">
            <div class="flex justify-between text-xs text-gray-700">
                <div class="text-center">
                    <div class="text-xl font-bold text-green-600 mb-1">{{$totalBerat !== 0 ? number_format($totalBerat, 0, ',', '.') : 0 }}</div>
                    <div>Total Kg</div>
                </div>
                <div class="text-center">
                    <div class="text-xl font-bold text-green-600 mb-1">{{$totalBsu !== 0 ? number_format($totalBsu, 0, ',', '.') : 0 }}</div>
                    <div>BSU</div>
                </div>
                <div class="text-center">
                    <div class="text-xl font-bold text-green-600 mb-1">Rp {{$totalSaldo !== 0 ? number_format($totalSaldo, 0, ',', '.') : 0 }}</div>
                    <div>Nilai</div>
                </div>
            </div>
        </div>
        
        <!-- List of other players -->
        <div class="px-4 py-2">
            <!-- Player 4 -->
            @foreach ($transactionsPerBSU as $i => $b)
                
            <div class="flex items-center bg-gray-50 p-3 rounded-lg mb-2 hover:bg-gray-100 transition">
                <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center font-bold text-white mr-3">{{$i+1}}</div>
                <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center bg-gray-200">
                    <i class="fas fa-seedling text-green-600 text-lg"></i>
                </div>
                <div class="ml-3 flex-grow">
                    <p class="text-gray-800 font-medium">{{$b->bsu_name}}</p>
                    
                </div>
                <div class="text-right">
                    <p class="text-green-600 font-semibold">{{ number_format($b->total_berat, 2, ',', '.') }} kg</p>
                </div>
            </div>
            @endforeach
           
        </div>
    </div>
</div>

            <!-- Header -->
     
        @endsection

    