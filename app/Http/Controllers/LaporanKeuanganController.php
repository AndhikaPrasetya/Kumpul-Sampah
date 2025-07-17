<?php

namespace App\Http\Controllers;

use App\Models\BsuDetail;
use App\Models\KelurahanDetails;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $title = "Data Laporan";
        $breadcrumb = "Laporan";

        $user = auth()->user(); // Dapatkan pengguna yang sedang login

        // Dapatkan daftar nasabah berdasarkan role BSU (jika BSU)
        // Ini digunakan untuk dropdown filter di view, bukan untuk query DataTables utama
        $nasabahs = collect(); // Default koleksi kosong
        if ($user->hasRole('bsu')) {
            // Asumsi getNasabahUsers() mengambil nasabah yang terkait dengan bsu_id user yang login
            $nasabahs = $this->getNasabahUsers($user->id);
        }
        // Jika role kelurahan, $nasabahs akan tetap kosong atau Anda bisa mengambil semua nasabah
        // untuk dropdown filter di sisi kelurahan.
        // Contoh: if ($user->hasRole('kelurahan')) { $nasabahs = User::role('nasabah')->get(); }


        if ($request->ajax()) {
            // Mulai query utama untuk DataTables
            $data = TransactionDetail::with(['transaction.users', 'sampah']);

            // Filter berdasarkan role pengguna yang login
            if ($user->hasRole('bsu')) {
                // Jika BSU, hanya tampilkan transaksi yang bsu_id-nya sesuai dengan user BSU yang login
                $data->whereHas('transaction', function ($query) use ($user) {
                    $query->where('bsu_id', $user->id);
                });
            }
            // Jika role-nya 'kelurahan', tidak perlu menambahkan filter bsu_id,
            // sehingga akan mengambil semua data transaksi (sesuai permintaan "all data").

            // Filter status 'approved' selalu diterapkan pada transaksi
            $data->whereHas('transaction', function ($query) {
                $query->where('status', 'approved');
            });


            // Filter pencarian (search)
            if ($search = $request->input('search.value')) {
                $data->where(function ($query) use ($search) {
                    $query->whereHas('sampah', function ($subQuery) use ($search) { // Menggunakan $subQuery
                        $subQuery->where('nama', 'like', "%{$search}%");
                    })
                        ->orWhereHas('transaction.users', function ($subQuery) use ($search) { // Menggunakan $subQuery
                            $subQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhere('created_at', 'like', "%{$search}%");
                });
            }

            // Filter kustom dari request (nama_nasabah, tanggal, status)
            $data->when($request->filled('nama_nasabah'), function ($query) use ($request) {
                $query->whereHas('transaction.users', function ($subQuery) use ($request) {
                    $subQuery->whereIn('name', (array) $request->nama_nasabah); // Pastikan $request->nama_nasabah adalah array
                });
            })
                ->when($request->filled('start_date') && $request->filled('end_date'), function ($query) use ($request) {
                    // Asumsi 'tanggal' adalah kolom di TransactionDetail atau di Transaction
                    // Jika 'tanggal' ada di Transaction, ubah ke whereHas('transaction', ...)
                    $query->whereBetween('created_at', [ // Menggunakan created_at dari TransactionDetail
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->end_date)->endOfDay()
                    ]);
                })
                ->when($request->filled('status'), function ($query) use ($request) {
                    $query->whereHas('transaction', function ($subQuery) use ($request) {
                        $subQuery->whereIn('status', (array) $request->status); // Pastikan $request->status adalah array
                    });
                });

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('nasabah_name', function ($data) { // Ubah nama kolom agar lebih jelas
                    return $data->transaction->users->name ?? 'N/A'; // Tambahkan null coalescing operator
                })
                ->addColumn('sampah_name', function ($data) { // Ubah nama kolom agar lebih jelas
                    return $data->sampah->nama ?? 'N/A';
                })
                ->addColumn('berat', function ($data) {
                    return number_format($data->berat, 0, ',', '.') . ' KG'; // Tambahkan spasi
                })
                ->addColumn('subtotal', function ($data) {
                    return 'Rp ' . number_format($data->subtotal, 0, ',', '.');
                })
                ->addColumn('points', function ($data) {
                    return number_format($data->points, 0, ',', '.');
                })
                ->addColumn('tanggal', function ($data) {
                    // Pastikan kolom tanggal yang benar digunakan (created_at atau kolom tanggal lain)
                    return Carbon::parse($data->created_at)->format('d-m-Y');
                })
                // Jika Anda memiliki kolom 'status' di TransactionDetail, tambahkan di sini
                // Jika status hanya ada di Transaction, Anda bisa menambahkannya dengan:
                ->addColumn('status_transaksi', function ($data) {
                    return $data->transaction->status ?? 'N/A';
                })
                ->rawColumns(['status_transaksi']) // Sesuaikan jika ada kolom lain yang perlu raw
                ->make(true);
        }
        return view('dashboard.laporan-keuangan.index', get_defined_vars());
    }
    private function getNasabahUsers($bsuId)
    {
        return User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'nasabah');
            })
            ->whereHas('nasabahs', function ($query) use ($bsuId) {
                $query->where('bsu_id', $bsuId);
            })
            ->get();
    }

    public function getReport(Request $request)
    {
        $reportType = $request->query('report_type');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $user = Auth::user(); // Dapatkan pengguna yang sedang login
        // Tentukan apakah filtering berdasarkan bsu_id diperlukan
        // Filter hanya dilakukan jika pengguna memiliki role 'bsu'
        $shouldFilterByBsuId = $user->hasRole('bsu');

        if ($reportType == 'transaction') {
            $data = Transactions::with(['users', 'details']);

            // Terapkan filter bsu_id kondisional
            if ($shouldFilterByBsuId) {
                $data->where('bsu_id', $user->id); // Filter jika role adalah BSU
            }
  $data->where('status', 'approved'); 
            

            // Filter berdasarkan tanggal jika dipilih
            if ($startDate && $endDate) {
                $data->whereBetween('updated_at', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ]);
            }

            $data->orderBy('updated_at', 'desc'); // Order by terbaru

            $data = $data->get();
            return view('dashboard.laporan-keuangan.partials.transaction_table', compact('data'))->render();
        } elseif ($reportType == 'withdraw') {
            $data = Withdraw::with('user');

            // Terapkan filter bsu_id kondisional
            if ($shouldFilterByBsuId) {
                $data->where('bsu_id', $user->id); // Filter jika role adalah BSU
            }

            // Filter berdasarkan tanggal jika dipilih
            if ($startDate && $endDate) {
                $data->whereBetween('created_at', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ]);
            }

            $data = $data->get();
            return view('dashboard.laporan-keuangan.partials.withdraw_table', compact('data'))->render();
        }

        return response()->json(['error' => 'Invalid Report Type'], 400);
    }

public function exportReportToPDF(Request $request)
{
    $user = Auth::user();
    $userDetail = null;

    // Ambil detail pengguna berdasarkan role
    if ($user->hasRole('bsu')) {
        $userDetail = BsuDetail::with('user')->where('user_id', $user->id)->first();
    } elseif ($user->hasRole('kelurahan')) {
        $userDetail = KelurahanDetails::with('user')->where('user_id', $user->id)->first();
    }

    $reportType = $request->query('report_type');
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');

    if ($reportType == 'transaction') {
        $data = Transactions::with(['users', 'details']);

        if ($user->hasRole('bsu')) {
            $data->where('bsu_id', $user->id);
        }

        $data->where('status', 'approved');

        if ($startDate && $endDate) {
            $data->whereBetween('updated_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $data = $data->get();

        // Gunakan view berbeda untuk kelurahan dan bsu
        $viewPath = $user->hasRole('kelurahan') 
            ? 'dashboard.laporan-keuangan.kelurahan.pdf.transaction_pdf'
            : 'dashboard.laporan-keuangan.pdf.transaction_pdf';

        $pdf = Pdf::loadView($viewPath, [
            'user' => $userDetail,
            'data' => $data,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return $pdf->download('laporan-transaksi.pdf');

    } elseif ($reportType == 'withdraw') {
        $data = Withdraw::with('user');

        if ($user->hasRole('bsu')) {
            $data->where('bsu_id', $user->id);
        }

        if ($startDate && $endDate) {
            $data->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $data = $data->get();

        // View khusus untuk kelurahan
        $viewPath = $user->hasRole('kelurahan') 
            ? 'dashboard.laporan-keuangan.kelurahan.pdf.withdraw_pdf'
            : 'dashboard.laporan-keuangan.pdf.withdraw_pdf';

        $pdf = Pdf::loadView($viewPath, [
            'user' => $userDetail,
            'data' => $data,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return $pdf->download('laporan-penarikan.pdf');
    }

    return response()->json(['error' => 'Invalid Report Type'], 400);
}


}
