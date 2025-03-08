<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TransactionFrontendController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->input('status');
        // Using a collection instead for section transactions
        $withdrawals = DB::table('withdraws')->select('id', 'user_id', 'status', 'amount', 'created_at')->get();
        $pointExchanges = DB::table('penukaran_points')->select('id', 'user_id', 'reward_id', 'status', 'total_points', 'created_at')->get();
        $wasteDeposits = DB::table('transactions')->select('id', 'transaction_code', 'user_id', 'status', 'total_amount', 'total_points', 'created_at')->get();

        if ($statusFilter && $statusFilter !== 'all') {
            $withdrawals->where('status', $statusFilter);
            $pointExchanges->where('status', $statusFilter);
            $wasteDeposits->where('status', $statusFilter);
        }
    
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
        $transactions = $withdrawalsWithType->concat($pointExchangesWithType)->concat($wasteDepositsWithType)
            ->sortByDesc('created_at');
        return view('frontend.transaction.list', compact('transactions','statusFilter'));
    }


}
