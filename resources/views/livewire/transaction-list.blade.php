<div class="section full mb-3" >
    <div class="section-heading padding">
        <h2 class="title">Transaksi</h2>
        <a href="{{ route('transaksiFrontend.index') }}" class="link">Lihat semua</a>
    </div>

    @if ($transactions->isNotEmpty())
        @foreach ($transactions as $transaction)
            <div class="card m-2 p-1 mb-2 shadow-sm">
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
                        <small class="text-muted d-block">
                            {{ \Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y') }}
                        </small>
                        <small class="{{ $badgeClass }}">{{ ucfirst($transaction->status) }}</small>
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
                            <small class="text-success">
                                + Rp. {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </small>
                        @endif

                        {{-- Untuk transaksi tukar_points --}}
                        @if ($transaction->type == 'tukar_points')
                            <small class="d-block text-danger">
                                {{ $transaction->total_points > 0 ? '-' : '' }}{{ $transaction->total_points }} poin
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="title p-3">Belum ada transaksi</div>
    @endif
</div>