@extends('layouts.layout-fe')
@section('title', 'Transaksi')
@section('content')
<div id="appCapsule">
    <div class="d-flex gap-2 mt-2" style="overflow-x: auto; margin-left:5px;">

        <div class="dropdown-status">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Semua status
            </button>
            <div class="dropdown-menu" style="">
                <a class="dropdown-item" href="#">Send</a>
                <a class="dropdown-item" href="#">Deposit</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Cancel</a>
            </div>
        </div>
        <div class="dropdown-tanggal">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Semua tanggal
            </button>
            <div class="dropdown-menu" style="">
                <a class="dropdown-item" href="#">Send</a>
                <a class="dropdown-item" href="#">Deposit</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Cancel</a>
            </div>
        </div>
        <div class="dropdown-jenis">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Semua Transaksi
            </button>
            <div class="dropdown-menu" style="">
                <a class="dropdown-item" href="#">Send</a>
                <a class="dropdown-item" href="#">Deposit</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Cancel</a>
            </div>
        </div>
    </div>
    <!-- Transactions -->
    <div class="section mt-2">
        @foreach ($transactions as $transaction)
        <div class="card p-3 mb-2 shadow-sm">
            <div class="d-flex align-items-center">
                {{-- Menentukan ikon dan judul berdasarkan jenis transaksi --}}
                @php
                    $icons = [
                        'tarik_tunai' => asset('/template-fe/assets/img/withdraw.png'),
                        'tukar_points' => asset('/template-fe/assets/img/coin.png'),
                        'setor_sampah' => asset('/template-fe/assets/img/recycle.png'),
                    ];

                    $titles = [
                        'tarik_tunai' => 'Tarik Tunai',
                        'tukar_points' => 'Tukar Points',
                        'setor_sampah' => 'Setor Sampah',
                    ];
                    $badgeClass = match ($transaction->status) {
                        'approved' => 'badge badge-success',
                        'rejected' => 'badge badge-danger',
                        'pending' => 'badge badge-warning',
                        default => 'badge bg-secondary',
                    };
                    $icon = $icons[$transaction->type] ?? 'default-icon.png';
                    $title = $titles[$transaction->type] ?? 'Transaksi';
                @endphp

                <img src="{{ $icon }}" alt="icon" class="me-3" width="40">
                <div class="flex-grow-1">
                    <h5 class="mb-1">{{ $title }}</h5>
                    <small
                        class="text-muted d-block">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y') }}</small>
                    <small class="{{$badgeClass}}">{{ ucfirst($transaction->status) }}</small>
                </div>

                <div class="text-end">
                    {{-- Untuk transaksi tarik_tunai --}}
                    @if ($transaction->type == 'tarik_tunai')
                        <small class="text-danger">
                            - Rp. {{ number_format($transaction->amount, 0, ',', '.') }}
                        </small>
                    @endif

                    {{-- Untuk transaksi setor_sampah --}}
                    @if ($transaction->type == 'setor_sampah')
                        @if ($transaction->status == 'approved')
                            <small class="text-success">
                                + Rp. {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </small>
                        @else
                            <small class="text-muted">Pending</small>
                        @endif
                    @endif

                    {{-- Untuk transaksi tukar_points --}}
                    @if ($transaction->type == 'tukar_points')
                        <small class="d-block text-danger">
                            {{ $transaction->total_points > 0 ? '-' : '' }}{{ $transaction->total_points }}
                            poin
                        </small>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
    
    </div>
    <!-- * Transactions -->


</div>
@endsection