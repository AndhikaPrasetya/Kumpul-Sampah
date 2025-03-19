@extends('layouts.layoutSecond')
@section('headTitle', 'Rewards')
@section('title', 'Rewards')
@section('content')

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-2 gap-3 md:gap-4 px-2">
    @foreach ($rewards as $index => $reward)
        <div class="reward-item {{ $index >= 6 ? 'hidden sm:block' : '' }}">
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 h-full">
                <div class="flex justify-center pt-4 pb-2">
                    <img src="{{ asset($reward->image) }}" alt="{{ $reward->name }}" class="h-20 sm:h-24 w-auto object-contain">
                </div>
                <div class="p-3 sm:p-4 text-center">
                    <h5 class="text-base sm:text-lg font-medium text-gray-800 mb-1 line-clamp-1">{{ $reward->name }}</h5>
                    <span class="inline-block bg-green-100 text-green-800 text-xs sm:text-sm font-medium px-2 sm:px-3 py-1 rounded-full mb-2 sm:mb-3">
                        {{ number_format($reward->points, 0, ',', '.') }} Poin
                    </span>
                    <div class="mt-2">
                        <a href="{{route('detailReward', $reward->id)}}" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium text-sm sm:text-base py-1.5 sm:py-2 px-3 sm:px-4 rounded-lg transition-colors duration-300">
                            Tukar Reward
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
