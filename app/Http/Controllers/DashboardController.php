<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Withdraw;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    //hitung total sampah dari transactions 
    public function total(Request $request)
    {
        $bsu_id = Auth::id();
        $tahun = $request->query('year', date('Y')); // Ambil tahun dari request, default tahun ini

        // Set TTL (time to live) untuk cache dalam hitungan detik (misalnya 5 menit)
        $cacheTime = 300; // 300 detik = 5 menit

        $isAdmin = Auth::user()->hasRole('super admin');

        $totalSampah = Cache::remember("total_sampah_" . ($isAdmin ? 'all' : $bsu_id), $cacheTime, function () use ($bsu_id, $isAdmin) {
            return TransactionDetail::whereHas('transaction', function ($query) use ($bsu_id, $isAdmin) {
                $query->where('status', 'approved');
        
                if (!$isAdmin) {
                    $query->where('bsu_id', $bsu_id); // Hanya filter jika bukan admin
                }
            })->sum('berat');
        });
        
        $totalSaldo = Cache::remember("total_saldo_" . ($isAdmin ? 'all' : $bsu_id), $cacheTime, function () use ($bsu_id, $isAdmin) {
            $totalMasuk = Transactions::where('status', 'approved');
        
            if (!$isAdmin) {
                $totalMasuk->where('bsu_id', $bsu_id);
            }
        
            $totalMasuk = $totalMasuk->sum('total_amount');
        
            $totalKeluar = Withdraw::where('status', 'approved');
        
            if (!$isAdmin) {
                $totalKeluar->where('bsu_id', $bsu_id);
            }
        
            $totalKeluar = $totalKeluar->sum('amount');
        
            return $totalMasuk - $totalKeluar;
        });
        
        $totalSaldoKeluar = Cache::remember("total_saldo_keluar_" . ($isAdmin ? 'all' : $bsu_id), $cacheTime, function () use ($bsu_id, $isAdmin) {
            $query = Withdraw::where('status', 'approved');
        
            if (!$isAdmin) {
                $query->where('bsu_id', $bsu_id);
            }
        
            return $query->sum('amount');
        });
        
        $transactions = Cache::remember("transactions_pending_" . ($isAdmin ? 'all' : $bsu_id), $cacheTime, function () use ($bsu_id, $isAdmin) {
            $query = Transactions::with(['users', 'details'])->where('status', 'pending');
        
            if (!$isAdmin) {
                $query->where('bsu_id', $bsu_id);
            }

            return $query->get();
        });
        
        
        $sampahData = Cache::remember("grafik_sampah_" . ($isAdmin ? 'all' : $bsu_id), $cacheTime, function () use ($bsu_id, $isAdmin) {
            return TransactionDetail::join('sampahs', 'transaction_details.sampah_id', '=', 'sampahs.id')
            ->join('category_sampahs', 'sampahs.category_id', '=', 'category_sampahs.id')
            ->whereHas('transaction', function ($query) use ($bsu_id, $isAdmin) {
                $query->where('status', 'approved');
        
                if (!$isAdmin) {
                    $query->where('bsu_id', $bsu_id);
                }
            })
            ->selectRaw('category_sampahs.nama as category_name, COUNT(*) as jumlah')
            ->groupBy('category_sampahs.nama')
            ->get();
        });

        $totalNasabah = Cache::remember("total_nasabah" . ($isAdmin ? 'all' : $bsu_id), $cacheTime, function () use ($bsu_id, $isAdmin) {
            return User::whereHas('roles', function ($query) {
                    $query->where('name', 'nasabah');
                })
                ->when(!$isAdmin, function ($query) use ($bsu_id) {
                    $query->whereHas('nasabahs', function ($subQuery) use ($bsu_id) {
                        $subQuery->where('bsu_id', $bsu_id);
                    });
                })
                ->count(); // Hitung jumlah nasabah
        });
        $nasabahPerBulan = Transactions::selectRaw('MONTH(created_at) as bulan, COUNT(DISTINCT user_id) as jumlah')
        ->whereYear('created_at', now()->year)
        ->when(!$isAdmin, function ($query) use ($bsu_id) {
            $query->whereHas('users.nasabahs', function ($subQuery) use ($bsu_id) {
                $subQuery->where('bsu_id', $bsu_id);
            });
        })
        ->groupByRaw('MONTH(created_at)')
        ->get()
        ->keyBy('bulan');
        $bulanLabels = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    // Pastikan semua bulan ada
    $jumlahNasabahPerBulan = [];
    for ($bulan = 1; $bulan <= 12; $bulan++) {
        $jumlahNasabahPerBulan[] = [
            'bulan' => $bulanLabels[$bulan],
            'jumlah' => $nasabahPerBulan[$bulan]->jumlah ?? 0
        ];
    }
   
        return view('dashboard.index', get_defined_vars());
    }

    public function filterCharts(Request $request)
    {
        $year = $request->input('year');
        $isAdmin = Auth::user()->hasRole('Admin');
        $bsu_id = Auth::user()->nasabahs->first()->bsu_id ?? null;
        
        // Gunakan tahun dari filter jika ada, jika tidak gunakan tahun sekarang
        $selectedYear = $year ? $year : now()->year;
        
        $nasabahPerBulan = Transactions::selectRaw('MONTH(created_at) as bulan, COUNT(DISTINCT user_id) as jumlah')
            ->whereYear('created_at', $selectedYear) // Gunakan $selectedYear daripada hardcode now()->year
            ->when(!$isAdmin, function ($query) use ($bsu_id) {
                $query->whereHas('users.nasabahs', function ($subQuery) use ($bsu_id) {
                    $subQuery->where('bsu_id', $bsu_id);
                });
            })
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->keyBy('bulan');
            
        $bulanLabels = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        // Pastikan semua bulan ada
        $jumlahNasabahPerBulan = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $jumlahNasabahPerBulan[] = [
                'bulan' => $bulanLabels[$bulan],
                'jumlah' => $nasabahPerBulan[$bulan]->jumlah ?? 0
            ];
        }
        
        // Hapus dd() untuk production
        // dd($jumlahNasabahPerBulan);
        
        return response()->json([
            'jumlahNasabahPerBulan' => $jumlahNasabahPerBulan
        ]);
    }

}
