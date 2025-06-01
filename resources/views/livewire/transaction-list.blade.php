<section class="mt-4" wire:poll.5s="loadTransactions">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-800">Transaksi</h2>
        <a href="{{ route('transaksiFrontend.index') }}" class="text-green-600 hover:text-green-700 transition text-sm font-medium">
            Lihat Semua
        </a>
    </div>

    <div class="flex items-center justify-between p-1 ">
      @if ($transactions->isNotEmpty())
        <div class="w-full">
          @foreach ($transactions as $transaction)
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
                'approved' => 'bg-green-100 text-green-800',
                'rejected' => 'bg-red-100 text-red-800',
                'pending' => 'bg-yellow-100 text-yellow-800',
                default => 'bg-gray-100 text-gray-800',
              };
    
              $routes = [
                'tarik_tunai' => route('transaction.withdraw', $transaction->transaction_code),
                'tukar_points' => route('transaction.exchange', $transaction->transaction_code),
                'setor_sampah' => route('transaction-details', $transaction->transaction_code),
              ];
              $url = $routes[$transaction->type] ?? '#';
              $icon = $icons[$transaction->type] ?? 'default-icon.png';
              $title = $titles[$transaction->type] ?? 'Transaksi';
            @endphp
          
            <a href="{{ $url }}" class="block hover:bg-gray-50 transition-colors duration-150">
              <div class="p-4 bg-white mb-3 rounded-lg shadow-lg">
                <div class="flex items-center">
                  <!-- Icon -->
                  <div class="flex-shrink-0 bg-gray-800 rounded-full p-2 mr-3">
                    <img src="{{ $icon }}" alt="icon" class="w-6 h-6">
                  </div>
    
                  <!-- Content -->
                  <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-center">
                      <h3 class="text-base font-semibold text-gray-900 truncate">{{ $title }}</h3>
                      
                      @if ($transaction->type == 'tarik_tunai')
                        <span class="text-red-600 font-medium">- Rp. {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                      @elseif ($transaction->type == 'setor_sampah')
                        <span class="text-green-600 font-medium">+ Rp. {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                      @elseif ($transaction->type == 'tukar_points')
                        <span class="text-red-600 font-medium">{{ $transaction->total_points > 0 ? '-' : '' }}{{ number_format($transaction->total_points, 0, ',', '.') }} poin</span>
                      @endif
                    </div>
    
                    <!-- Date and Status -->
                    <div class="flex justify-between items-center mt-1">
                      <span class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y') }}
                      </span>
                      <span class="text-xs px-2.5 py-0.5 rounded-full {{ $badgeClass }}">
                        {{ ucfirst($transaction->status) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          @endforeach
        </div>
    </div>
      @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl p-6 text-center w-full shadow-2xl">
         
            <svg class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
          
          <p class="text-gray-500">Belum ada transaksi</p>
        </div>
      @endif
  </section>