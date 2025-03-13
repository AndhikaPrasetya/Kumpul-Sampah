<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Models\Withdraw;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    //hitung total sampah dari transactions 
    public function total()
    {
        $bsu_id = Auth::id();

        // Set TTL (time to live) untuk cache dalam hitungan detik (misalnya 5 menit)
        $cacheTime = 300; // 300 detik = 5 menit

        $totalSampah = Cache::remember("total_sampah_{$bsu_id}", $cacheTime, function () use ($bsu_id) {
            return TransactionDetail::whereHas('transaction', function ($query) use ($bsu_id) {
                $query->where(['status' => 'approved', 'bsu_id' => $bsu_id]);
            })->sum('berat');
        });

        $totalSaldo = Cache::remember("total_saldo_{$bsu_id}", $cacheTime, function () use ($bsu_id) {
            $totalMasuk = Transactions::where('bsu_id', $bsu_id)
                ->where('status', 'approved')
                ->sum('total_amount');
        
            $totalKeluar = Withdraw::where('bsu_id', $bsu_id)
                ->where('status', 'approved')
                ->sum('amount');
            return $totalMasuk - $totalKeluar; // Saldo dikurangi saldo keluar
        });
        
        $totalSaldoKeluar =  Cache::remember("total_saldo_keluar_{$bsu_id}", $cacheTime, function () use ($bsu_id) {
            return Withdraw::where('bsu_id', $bsu_id)
                ->where('status', 'approved')
                ->sum('amount');
        });

        $transactions = Cache::remember("transactions_pending_{$bsu_id}", $cacheTime, function () use ($bsu_id) {
            return Transactions::with(['users', 'details'])
                ->where(['bsu_id' => $bsu_id, 'status' => 'pending'])
                ->get();
        });
        return view('dashboard.index', get_defined_vars());
    }
}
