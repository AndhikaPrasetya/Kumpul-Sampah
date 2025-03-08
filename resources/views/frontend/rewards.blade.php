@extends('layouts.layout-fe')
@section('title', 'Rewards')
@section('content')

<div id="appCapsule">
    <div class="section tab-content mt-2 mb-2">
        <div class="row">
            @foreach ($rewards as $index => $reward)
                <div class="col-6 mb-2 reward-item {{ $index >= 6 ? 'hidden' : '' }}">
                    <div class="blog-card">
                        <div class="image-wrapper d-flex justify-content-center">
                            <img src="{{ asset($reward->image) }}" alt="image" class="imaged w86">
                        </div>
                        <div class="text-wrapper p-2">
                            <div class="mb-1 text-center">
                                <h5>{{ $reward->name }}</h5>
                                <span class="badge badge-warning">{{ number_format($reward->points, 0, ',', '.') }} Poin</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if(count($rewards) > 6)
        <div>
            <a href="javascript:void(0)" class="btn btn-block btn-primary btn-lg" id="load-more">Load More</a>
        </div>
        @endif
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
