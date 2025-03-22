@extends('layouts.layoutMain')
@section('title', 'Harga Sampah Terkini')
@section('content')
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <!-- Header Tabel -->
    <div class="grid grid-cols-3 gap-4 p-4 border-b border-gray-100 font-semibold text-sm text-gray-600">
        <div>Jenis Sampah</div>
        <div class="text-right">Harga/kg</div>
        <div class="text-right">Points/kg</div>
    </div>

    <!-- Baris Data Sampah -->
    <div class="divide-y divide-gray-100">
        <!-- Item 1 -->
        @foreach ($sampahs as $sampah)
            
        <div class="grid grid-cols-3 gap-4 p-4 items-center hover:bg-gray-50 transition-colors">
            <div class="flex items-center">
                <span class="font-medium">{{$sampah->nama}}</span>
            </div>
            <div class="text-right text-green-600 font-medium">Rp {{ number_format($sampah->harga, 0, ',', '.') }}</div>
            <div class="text-right text-gray-600">{{ number_format($sampah->points, 0, ',', '.') }}</div>
        </div>
        @endforeach
    </div>
</div>
@endsection
