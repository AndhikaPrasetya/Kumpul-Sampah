@extends('layouts.layoutSecond')
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
        <div class="flex items-center space-x-3 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
            </svg>
            <div>
                <p class="text-gray-600 text-sm">Tanggal</p>
                <p class="font-medium text-gray-800 text-sm">
                    {{ \Carbon\Carbon::parse($tukarPoints->created_at)->format('d M Y') }}
                </p>
            </div>
        </div>

        <!-- Total -->
        <div class="flex items-center space-x-3 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
            </svg>
            <div>
                <p class="text-gray-600 text-sm">Total</p>
                <p class="font-bold text-emerald-500 text-sm">
                    {{ number_format($tukarPoints->total_points, 0, ',', '.') }} Points
                </p>
            </div>
        </div>

        <!-- Status -->
        <div class="flex items-center space-x-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <div>
                <p class="text-gray-600 text-sm">Status</p>
               
                <span class="text-sm font-medium">
                    {{ ucfirst($tukarPoints->status) }}
                </span>
            </div>
        </div>

        <!-- Tanggal Proses -->
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-gray-600 text-sm">Tanggal Proses</p>
            <p class="font-medium text-gray-800 text-sm">
                {{ in_array($tukarPoints->status, ["approved", "rejected"]) 
                    ? \Carbon\Carbon::parse($tukarPoints->updated_at)->format('d M Y, H:i')
                    : '-' }}
            </p>
        </div>
    </div>
</div>

@endsection