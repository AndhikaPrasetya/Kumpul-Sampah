@extends('layouts.layoutMain',['noBottomMenu' => true])
@section('headTitle', 'Rewards')
@section('title', 'Rewards')
@section('content')

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-2 gap-3 md:gap-4 px-2">
    @foreach ($rewards as $index => $reward)
        @php
            // Hitung progress poin user terhadap poin reward
            $progressPercentage = min(100, ($currentPoints / $reward->points) * 100);
            // Hitung poin yang masih dibutuhkan
            $pointsNeeded = max(0, $reward->points - $currentPoints);
        @endphp
        <div class="reward-item {{ $index >= 6 ? 'hidden sm:block' : '' }}">
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 h-full flex flex-col">
                <div class="flex justify-center pt-4 pb-2">
                    <img src="{{ secure_asset($reward->image) }}" alt="{{ $reward->name }}" class="h-20 sm:h-24 w-auto object-contain">
                </div>
                <div class="p-3 sm:p-4 text-center flex-grow">
                    <h5 class="text-base sm:text-lg font-medium text-gray-800 mb-1 line-clamp-1">{{ $reward->name }}</h5>
                    
                    <!-- Informasi Poin -->
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-coins text-yellow-500 me-1"></i>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">
                            {{ number_format($reward->points, 0, ',', '.') }} Poin
                        </span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mb-3">
                        <div class="relative flex bg-gray-200 rounded-full h-5 mb-2">
                            <div 
                                class="bg-green-500 h-5 rounded-full flex items-center justify-end pr-2 transition-all duration-500 ease-out"
                                style="width: {{ $progressPercentage }}%; min-width: 2rem;"
                            >
                                <span class="text-white text-xs font-bold">
                                    {{ round($progressPercentage) }}%
                                </span>
                            </div>
                            <!-- Teks untuk progress 0% -->
                            @if($progressPercentage < 10)
                                <span class="absolute text-xs text-gray-600 font-bold left-2 top-1/2 transform -translate-y-1/2">
                                    {{ round($progressPercentage) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Informasi Stok -->
                    @if($reward->stok > 0)
                        <div class="text-xs text-gray-500 mb-3">
                            Stok tersedia: {{ $reward->stok }}
                        </div>
                    @else
                        <div class="text-xs text-red-500 mb-3">
                            Stok habis
                        </div>
                    @endif
                    
                    <!-- Tombol Tukar -->
                    <div class="mt-auto">
                        <a href="{{route('detailReward', $reward->id)}}" class="block w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium text-sm sm:text-base py-1.5 sm:py-2 px-3 sm:px-4 rounded-lg transition-colors duration-300 @if($reward->stok <= 0) opacity-50 cursor-not-allowed @endif">
                            @if($reward->stok <= 0)
                                Stok habis
                            @else
                                Tukar Reward
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        let visibleCount = 6;
        let totalItems = $(".reward-item").length;

        $("#load-more").click(function() {
            $(".reward-item.hidden").slice(0, 6).removeClass("hidden"); // Tampilkan 6 item berikutnya
            visibleCount += 6;

            if (visibleCount >= totalItems) {
                $(this).hide(); // Sembunyikan tombol jika semua item telah ditampilkan
            }
        });
    });
</script>
@endsection
