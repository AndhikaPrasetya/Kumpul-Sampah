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
        $bsu_id = Auth::id(); // ID pengguna yang sedang login
        $user = Auth::user(); // Objek pengguna yang sedang login
        $tahun = $request->query('year', date('Y')); // Ambil tahun dari request, default tahun ini

        // Set TTL (time to live) untuk cache dalam hitungan detik (misalnya 5 menit)
        $cacheTime = 300; // 300 detik = 5 menit

        // Tentukan apakah filtering berdasarkan bsu_id diperlukan
        // Filtering hanya dilakukan jika pengguna memiliki role 'bsu'
        $shouldFilterByBsuId = $user->hasRole('bsu');

        // Tentukan suffix untuk kunci cache: 'all' jika tidak ada filtering bsu_id,
        // atau ID bsu_id jika filtering aktif.
        $cacheKeySuffix = $shouldFilterByBsuId ? $bsu_id : 'all';

        // --- Total Sampah ---
        $totalSampah = Cache::remember("total_sampah_" . $cacheKeySuffix . "_" . $tahun, $cacheTime, function () use ($user, $shouldFilterByBsuId, $tahun) {
            return TransactionDetail::whereHas('transaction', function ($query) use ($user, $shouldFilterByBsuId, $tahun) {
                $query->where('status', 'approved')
                      ->whereYear('created_at', $tahun); // Tambahkan filter tahun

                if ($shouldFilterByBsuId) {
                    $query->where('bsu_id', $user->id); // Hanya filter jika role adalah BSU
                }
            })->sum('berat');
        });
        
        // --- Total Saldo ---
        $totalSaldo = Cache::remember("total_saldo_" . $cacheKeySuffix . "_" . $tahun, $cacheTime, function () use ($user, $shouldFilterByBsuId, $tahun) {
            $totalMasukQuery = Transactions::where('status', 'approved')
                                            ->whereYear('created_at', $tahun); // Tambahkan filter tahun
        
            if ($shouldFilterByBsuId) {
                $totalMasukQuery->where('bsu_id', $user->id);
            }
            $totalMasuk = $totalMasukQuery->sum('total_amount');
        
            $totalKeluarQuery = Withdraw::where('status', 'approved')
                                        ->whereYear('created_at', $tahun); // Tambahkan filter tahun
        
            if ($shouldFilterByBsuId) {
                $totalKeluarQuery->where('bsu_id', $user->id);
            }
            $totalKeluar = $totalKeluarQuery->sum('amount');
        
            return $totalMasuk - $totalKeluar;
        });
        
        // --- Total Saldo Keluar ---
        $totalSaldoKeluar = Cache::remember("total_saldo_keluar_" . $cacheKeySuffix . "_" . $tahun, $cacheTime, function () use ($user, $shouldFilterByBsuId, $tahun) {
            $query = Withdraw::where('status', 'approved')
                             ->whereYear('created_at', $tahun); // Tambahkan filter tahun
        
            if ($shouldFilterByBsuId) {
                $query->where('bsu_id', $user->id);
            }
        
            return $query->sum('amount');
        });
        
        // --- Transaksi Pending ---
        $transactions = Cache::remember("transactions_pending_" . $cacheKeySuffix . "_" . $tahun, $cacheTime, function () use ($user, $shouldFilterByBsuId, $tahun) {
            $query = Transactions::with(['users', 'details'])
                                 ->where('status', 'pending')
                                 ->whereYear('created_at', $tahun); // Tambahkan filter tahun
        
            if ($shouldFilterByBsuId) {
                $query->where('bsu_id', $user->id);
            }

            return $query->get();
        });
        
        // --- Data Grafik Sampah per Kategori ---
        $sampahData = Cache::remember("grafik_sampah_" . $cacheKeySuffix . "_" . $tahun, $cacheTime, function () use ($user, $shouldFilterByBsuId, $tahun) {
            return TransactionDetail::join('sampahs', 'transaction_details.sampah_id', '=', 'sampahs.id')
                ->join('category_sampahs', 'sampahs.category_id', '=', 'category_sampahs.id')
                ->whereHas('transaction', function ($query) use ($user, $shouldFilterByBsuId, $tahun) {
                    $query->where('status', 'approved')
                          ->whereYear('created_at', $tahun); // Tambahkan filter tahun
            
                    if ($shouldFilterByBsuId) {
                        $query->where('bsu_id', $user->id);
                    }
                })
                ->selectRaw('category_sampahs.nama as category_name, COUNT(*) as jumlah')
                ->groupBy('category_sampahs.nama')
                ->get();
        });

        // --- Total Nasabah ---
        // Total nasabah tidak perlu filter tahun, kecuali jika nasabah dikaitkan dengan tahun tertentu
        $totalNasabah = Cache::remember("total_nasabah_" . $cacheKeySuffix, $cacheTime, function () use ($user, $shouldFilterByBsuId) {
            return User::whereHas('roles', function ($query) {
                        $query->where('name', 'nasabah');
                    })
                    ->when($shouldFilterByBsuId, function ($query) use ($user) {
                        $query->whereHas('nasabahs', function ($subQuery) use ($user) {
                            $subQuery->where('bsu_id', $user->id);
                        });
                    })
                    ->count();
        });

        // --- Nasabah Per Bulan ---
        // Ini sudah difilter by year dan role
        $nasabahPerBulan = Transactions::selectRaw('MONTH(created_at) as bulan, COUNT(DISTINCT user_id) as jumlah')
            ->whereYear('created_at', $tahun) // Menggunakan $tahun dari request
            ->when($shouldFilterByBsuId, function ($query) use ($user) {
                $query->whereHas('users.nasabahs', function ($subQuery) use ($user) {
                    $subQuery->where('bsu_id', $user->id);
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
        
        // Pastikan semua bulan ada, dengan jumlah 0 jika tidak ada data
        $jumlahNasabahPerBulan = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $jumlahNasabahPerBulan[] = [
                'bulan' => $bulanLabels[$bulan],
                'jumlah' => $nasabahPerBulan->get($bulan)->jumlah ?? 0 // Gunakan get($bulan)
            ];
        }
        
        // --- Lewatkan variabel ke view ---
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
