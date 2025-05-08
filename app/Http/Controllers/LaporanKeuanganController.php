<?php

namespace App\Http\Controllers;

use App\Models\BsuDetail;
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
        $bsuId = $request->user()->id;
        $nasabahs = $this->getNasabahUsers($bsuId);
        if ($request->ajax()) {
            $data = TransactionDetail::with(['transaction.users', 'sampah'])
            ->whereHas('transaction', function ($query) use($request) {
                $query->where('bsu_id', $request->user()->id)->where('status','approved');
            });

            if ($search = $request->input('search.value')) {
                $data->whereHas('sampah', function ($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%");
                })
                    ->orWhereHas('transaction.users', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('created_at', 'like', "%{$search}%");
            }

            $data->when($request->filled('nama_nasabah'), function ($query) use ($request) {
                $query->whereHas('transaction.users', function ($subQuery) use ($request) {
                    $subQuery->whereIn('name', $request->nama_nasabah);
                });
            })
                ->when($request->filled('start_date') && $request->filled('end_date'), function ($query) use ($request) {
                    $query->whereBetween('tanggal', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->end_date)->endOfDay()
                    ]);
                })
                ->when($request->filled('status'), function ($query) use ($request) {
                    $query->whereHas('transaction', function ($subQuery) use ($request) {
                        $subQuery->whereIn('status', $request->status);
                    });
                });
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('transaction_id', function ($data) {
                    return $data->transaction->users->name;
                })
                ->addColumn('sampah_id', function ($data) {
                    return $data->sampah->nama;
                })
                ->addColumn('berat', function ($data) {
                    return number_format($data->berat, 0, ',', '.') . 'KG ';
                })
                ->addColumn('subtotal', function ($data) {
                    return 'Rp ' . number_format($data->subtotal, 0, ',', '.');
                })
                ->addColumn('points', function ($data) {
                    return number_format($data->points, 0, ',', '.');
                })
                ->addColumn('tanggal', function ($data) {
                    return Carbon::parse($data->tanggal)->format('d-m-Y');
                })
                ->rawColumns(['status'])
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

    if ($reportType == 'transaction') {
        $data = Transactions::with(['users','details'])
            ->where('bsu_id', $request->user()->id)
            ->orderBy('created_at', 'desc');


        // Filter berdasarkan tanggal jika dipilih
        if ($startDate && $endDate) {
            $data->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $data = $data->get();
        return view('dashboard.laporan-keuangan.partials.transaction_table', compact('data'))->render();
    } 
    elseif ($reportType == 'withdraw') {
        $data = Withdraw::with('user')
            ->where('bsu_id', $request->user()->id);

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
    if(Auth::check()){
        $userId = Auth::id();
        $user = BsuDetail::with('user')->where('user_id', $userId)->first();
    }
    $reportType = $request->query('report_type');
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');

    if ($reportType == 'transaction') {
        $data = Transactions::with(['users', 'details'])
            ->where('bsu_id', $request->user()->id);

        if ($startDate && $endDate) {
            $data->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $data = $data->get();

        $pdf = Pdf::loadView('dashboard.laporan-keuangan.pdf.transaction_pdf', [
            'user' =>$user,
            'data' => $data,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return $pdf->download('laporan-transaksi.pdf');

    } elseif ($reportType == 'withdraw') {
        $data = Withdraw::with('user')
            ->where('bsu_id', $request->user()->id);

        if ($startDate && $endDate) {
            $data->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $data = $data->get();

        $pdf = Pdf::loadView('dashboard.laporan-keuangan.pdf.withdraw_pdf', [
            'user' =>$user,
            'data' => $data,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return $pdf->download('laporan-penarikan.pdf');
    }

    return response()->json(['error' => 'Invalid Report Type'], 400);
}



}
