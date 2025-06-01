<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionList extends Component
{
    public $transactions = [];

    public function mount()
    {
        $this->loadTransactions();
    }

    public function loadTransactions()
    {
        $user = Auth::user();

        // Mengambil data transaksi dari database
        $withdrawals = DB::table('withdraws')
            ->select('id', 'transaction_code', 'user_id', 'status', 'amount', 'created_at')
            ->where('user_id', $user->id)
            ->get();

        $pointExchanges = DB::table('penukaran_points')
            ->select('id', 'transaction_code', 'user_id', 'reward_id', 'status', 'total_points', 'created_at')
            ->where('user_id', $user->id)
            ->get();

        $wasteDeposits = DB::table('transactions')
            ->select('id', 'transaction_code', 'transaction_code', 'user_id', 'status', 'total_amount', 'total_points', 'created_at')
            ->where('user_id', $user->id)
            ->get();

        // Tambahkan properti type berdasarkan variabel yang digunakan
        $withdrawalsWithType = $withdrawals->map(function ($item) {
            $item->type = 'tarik_tunai';
            return $item;
        });

        $pointExchangesWithType = $pointExchanges->map(function ($item) {
            $item->type = 'tukar_points';
            return $item;
        });

        $wasteDepositsWithType = $wasteDeposits->map(function ($item) {
            $item->type = 'setor_sampah';
            return $item;
        });

        // Gabungkan dan urutkan
        $this->transactions = $withdrawalsWithType
            ->concat($pointExchangesWithType)
            ->concat($wasteDepositsWithType)
            ->sortByDesc('created_at')
            ->take(3);
    }

    public function render()
    {
        // Stop polling if not authenticated
    if (!auth()->check()) {
        return null;
    }
        return view('livewire.transaction-list');
    }
    
}