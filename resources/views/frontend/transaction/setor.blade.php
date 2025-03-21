@extends('layouts.layoutSecond')
@section('title', 'Setor Sampah')
@section('content')
    @php
        $icons = [
            'Plastik' => 'fa-recycle',
            'Bodong A' => 'fa-trash-alt',
            'Tutup Botol' => 'fa-wine-bottle',
            'Tutup Galon' => 'fa-prescription-bottle',
            'Ember Warna' => 'fa-box',
            'Ember Hitam' => 'fa-box',
            'Paralon' => 'fa-water',
            'Naso' => 'fa-question-circle',
            'Kresek' => 'fa-shopping-bag',
            'Galon Aqua' => 'fa-tint',
            'Akrilik' => 'fa-layer-group',
            'Gelas Kotor' => 'fa-glass-whiskey',
            'Inject' => 'fa-syringe',
            'Mainan' => 'fa-puzzle-piece',
        ];
    @endphp
    <div class="mb-20">
        <form action="{{ route('setor-sampah.store') }}" method="POST">
            @csrf
            <input type="hidden" name="total_amount" id="total_amount_hidden">
            <input type="hidden" name="total_points" id="total_points_hidden">

            @foreach ($kategoriSampah as $kategori)
                <div class="category-card bg-white rounded-xl shadow-sm mb-4 overflow-hidden">
                    <div class="flex justify-between items-center p-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3 text-red-500">
                                <i class="fas {{ $icons[$kategori->nama] ?? 'fa-trash' }} text-sm"></i>
                            </div>
                            <span class="font-medium">{{ $kategori->nama }}</span>
                        </div>
                    </div>

                    <div class="category-content">
                        @if (isset($groupedSampahs[$kategori->id]))
                            @foreach ($groupedSampahs[$kategori->id] as $sampah)
                                <div class="flex justify-between items-center p-3 border-b border-gray-100">
                                    <div>
                                        <div class="font-medium">{{ $sampah->nama }}</div>
                                        <div class="text-xs text-gray-400">Harga: Rp {{ number_format($sampah->harga, 0, ',', '.') }}/kg</div>
                                        <div class="text-xs text-gray-400">Points: {{ $sampah->points }}/kg</div>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="hidden" name="sampah_id[]" value="{{ $sampah->id }}">
                                        <input type="number" name="berat[]" class="berat-input w-16 text-center border" value="0" min="0" step="0.1">
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-3 text-center text-gray-400">Tidak ada sampah dalam kategori ini.</div>
                        @endif
                    </div>
                </div>
            @endforeach
            
            <div class="max-w-screen-sm mx-auto fixed bottom-5 left-5 right-5 bg-green-600 text-white p-3 rounded-lg shadow-lg flex justify-between items-center">
                <div class="text-sm">
                    <span class="font-medium">Total Sampah</span> | <span class="font-bold total-berat">0 Kg</span>
                </div>
                <button class="font-bold text-white" type="submit">Setor</button>
            </div>
        </form>
    </div>
@endsection
