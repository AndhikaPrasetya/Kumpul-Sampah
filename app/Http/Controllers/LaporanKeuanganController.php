<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanKeuanganController extends Controller
{
    public function index(){
        $title = 'Laporan Keuangan';
        $breadcrumb = 'Laporan Keuangan';
        return view('dashboard.laporan-keuangan.index',get_defined_vars());
    }
    public function filterLaporan(Request $request)
{
    $bulan = $request->bulan;
    $tahun = $request->tahun;

    // Dummy data untuk simulasi laporan keuangan
    $dummyData = [
        'total_nasabah' => rand(50, 200), // Random jumlah nasabah setor
        'total_saldo' => rand(10000000, 50000000), // Random total saldo dalam rupiah
        'total_sampah' => rand(1000, 5000) // Random total sampah dalam kg
    ];

    return response()->json($dummyData);
}

}
