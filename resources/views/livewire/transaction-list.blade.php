<div class="section full mb-3">
    <div class="section-heading padding">
        <h2 class="title">Transaksi</h2>
        <a href="{{ route('transaksiFrontend.index') }}" class="link">Lihat semua</a>
    </div>

    @if ($transactions->isNotEmpty())
        @foreach ($transactions as $transaction)
        @php
        // Menentukan ikon dan judul berdasarkan jenis transaksi
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
    
        // Menentukan class badge berdasarkan status
        $badgeClass = match ($transaction->status) {
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            'pending' => 'badge-warning',
            default => 'bg-secondary',
        };
    
        $icon = $icons[$transaction->type] ?? 'default-icon.png';
        $title = $titles[$transaction->type] ?? 'Transaksi';
    @endphp
    
    <a href="{{ route('transaction-details', $transaction->id) }}" class="text-decoration-none text-reset">
        <div class="card m-2 p-1 mb-2 shadowed" >
            <div class="card-body p-1">
                <div class="d-flex align-items-center">
                    <!-- Icon Wrapper -->
                    <div class="icon-wrapper p-1 bg-black w-10 imaged rounded d-flex align-items-center justify-content-center me-3">
                        <img src="{{ $icon }}" alt="icon" width="24" class="img-fluid">
                    </div>
    
                    <!-- Konten -->
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">{{ $title }}</h5>
    
                            <!-- Jumlah Transaksi -->
                            @if ($transaction->type == 'tarik_tunai')
                                <span class="text-danger fw-medium">- Rp. {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                            @elseif ($transaction->type == 'setor_sampah')
                                <span class="text-success fw-medium">+ Rp. {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                            @elseif ($transaction->type == 'tukar_points')
                                <span class="text-danger fw-medium">{{ $transaction->total_points > 0 ? '-' : '' }}{{ number_format($transaction->total_points, 0, ',', '.') }} poin</span>
                            @endif
                        </div>
    
                        <!-- Tanggal dan Status -->
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <small class="text-muted text-sm">
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y') }}
                            </small>
                            <span class="badge {{ $badgeClass }} rounded-pill px-3 py-1">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
        @endforeach
    @else
        <div class="title p-3">Belum ada transaksi</div>
    @endif
</div>

