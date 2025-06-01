<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Saldo;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Models\NasabahDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WitdhrawFeController extends Controller
{
    public function withdrawDetail(Request $request, $id)
    {
        $withdraw = Withdraw::findOrFail($id);
        return view('frontend.withdraw.detail', compact('withdraw'), [
            'route' => route('transaksiFrontend.index')
        ]);
    }

    public function withdraw()
    {
        $user = Auth::user();
        $saldoNasabah = Saldo::where('user_id', $user->id)->first();
        $saldoTertahan = Withdraw::where('user_id', $user->id)->where('status', 'pending')->sum('amount');
        return view('frontend.withdraw.create', get_defined_vars(), [
            'route' => route('home')
        ]);
    }

    public function withdrawStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'metode_penarikan' => 'required|in:transfer,cash'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $user = $request->user();
            $nasabahDetail = NasabahDetail::where('user_id', $user->id)->first();
            $bsuId = $nasabahDetail ? $nasabahDetail->bsu_id : null;
            $saldo = Saldo::where('user_id', $user->id)->first();

            if (!$saldo || $saldo->balance < $request->amount) {
                return response()->json(['error' => 'Saldo tidak cukup'], 400);
            }

            // Simpan data ke tabel withdrawals
           $withDraw = Withdraw::create([
                'user_id' => $user->id,
                'bsu_id' => $bsuId,
                'amount' => $request->amount,
                'metode_penarikan' =>$request->metode_penarikan,
                'tanggal' => now(),
                'status' => 'pending'
            ]);
            DB::commit();
            return response()->json(['message' => 'Permintaan penarikan berhasil','withdrawId'=>$withDraw->id], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan penarikan',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function waitingWIthdraw($id)
    {
        $user=Auth::user()->id;
        // Cari transaksi berdasarkan ID dan user_id dari user yang sedang login
        $withdraw = Withdraw::where('id', $id)
                                 ->where('user_id', $user)
                                 ->first();

        // Jika transaksi tidak ditemukan, redirect ke halaman lain
        if (!$withdraw) {
            return redirect()->route('transaksiFrontend.index')
                            ->with('error', 'Transaksi tidak ditemukan.');
        }

        // Kirim data transaksi ke view
        return view('frontend.withdraw.waiting',compact('withdraw'));
    }
}
