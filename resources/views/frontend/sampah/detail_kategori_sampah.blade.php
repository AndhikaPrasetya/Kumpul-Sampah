@extends('layouts.layoutMain',['noBottomMenu' =>true])
@section('title', 'Harga Sampah Terkini')
@section('content')
<div class="bg-gradient-to-br min-h-screen from-green-50 to-blue-50 p-4 max-w-3xl mx-auto">
  <!-- Header -->
  <div class="mb-6 text-center">
    <h1 class="text-2xl font-bold text-green-700">Daftar Harga Sampah</h1>
    <p class="text-sm text-gray-600">Update terbaru harga dan poin untuk setiap jenis sampah</p>
  </div>
@if ($sampahs->isNotEmpty())
@foreach ($sampahs->groupBy('kategori_nama') as $kategori => $items)
  <!-- Category Card -->
  <div class="mb-6 bg-white rounded-lg shadow-md overflow-hidden border border-green-100">
    <!-- Category Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-500 px-4 py-3 flex items-center">
      <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-3">
       
         <i class="fas fa-recycle text-white text-lg"></i>
       
      </div>
      <h2 class="text-lg font-bold text-white">{{ $kategori }}</h2>
    </div>

    <!-- Header -->
    <div class="grid grid-cols-3 gap-4 px-5 py-3 bg-gray-50 text-xs font-medium text-gray-600 border-b border-gray-100">
      <div>Jenis Sampah</div>
      <div class="text-right">Harga/kg</div>
      <div class="text-right">Points/kg</div>
    </div>

    <!-- Data -->
    <div>
      @foreach ($items as $sampah)
        <div class="grid grid-cols-3 gap-4 px-5 py-4 items-center hover:bg-green-50 transition-colors border-b border-gray-100 last:border-0">
          <div class="flex items-center">
            <span class="font-medium text-gray-800">{{ $sampah->nama }}</span>
          </div>
          <div class="text-right">
            <span class="bg-green-100 text-green-800 px-2 py-1 rounded font-medium">
              Rp {{ number_format($sampah->harga, 0, ',', '.') }}
            </span>
          </div>
          <div class="text-right">
            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded font-medium">
              {{ number_format($sampah->points, 0, ',', '.') }}
            </span>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endforeach
<div class="text-center text-gray-500 text-xs mt-6">
    <p>Harga dapat berubah sewaktu-waktu. Terakhir diperbarui: {{ date('d F Y') }}</p>
  </div>
@else
    <div class="text-center text-gray-500 text-sm">
        <p>Tidak ada  sampah </p>
    </div>
@endif

 
</div>

@endsection
