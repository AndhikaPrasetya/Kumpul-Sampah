<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionFrontendController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Using a collection instead for section transactions
        $withdrawals = DB::table('withdraws')->select('id', 'user_id', 'status', 'amount', 'created_at')->where('user_id', $user->id)->get();
        $pointExchanges = DB::table('penukaran_points')->select('id', 'user_id', 'reward_id', 'status', 'total_points', 'created_at')->where('user_id', $user->id)->get();
        $wasteDeposits = DB::table('transactions')->select('id', 'transaction_code', 'user_id', 'status', 'total_amount', 'total_points', 'created_at')->where('user_id', $user->id)->get();

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
        return view('frontend.transaction.list', compact('transactions'));
    }

    public function filter(Request $request)
    {
        $user = Auth::user();
        $status = $request->status;
        $days = $request->days;
        $jenisTransactions = $request->query('type');
        // Query Data
        $withdrawals = DB::table('withdraws')->where('user_id', $user->id);
        $pointExchanges = DB::table('penukaran_points')->where('user_id', $user->id);
        $wasteDeposits = DB::table('transactions')->where('user_id', $user->id);

        // Filter berdasarkan status
        if ($status) {
            $withdrawals->where('status', $status);
            $pointExchanges->where('status', $status);
            $wasteDeposits->where('status', $status);
        }

        // Filter berdasarkan tanggal
        if ($days) {
            if (is_numeric($days)) {
                $startDate = now()->subDays($days);
                $withdrawals->where('created_at', '>=', $startDate);
                $pointExchanges->where('created_at', '>=', $startDate);
                $wasteDeposits->where('created_at', '>=', $startDate);
            } else {
                $withdrawals->whereDate('created_at', '=', $days);
                $pointExchanges->whereDate('created_at', '=', $days);
                $wasteDeposits->whereDate('created_at', '=', $days);
            }
        }
        // Ambil data
        $withdrawals = $withdrawals->get()->map(function ($item) {
            $item->type = 'tarik_tunai';
            return $item;
        });

        $pointExchanges = $pointExchanges->get()->map(function ($item) {
            $item->type = 'tukar_points';
            return $item;
        });

        $wasteDeposits = $wasteDeposits->get()->map(function ($item) {
            $item->type = 'setor_sampah';
            return $item;
        });

        if ($jenisTransactions) {
            $withdrawals = $withdrawals->where('type', $jenisTransactions);
            $pointExchanges = $pointExchanges->where('type', $jenisTransactions);
            $wasteDeposits = $wasteDeposits->where('type', $jenisTransactions);
        }


        // Gabungkan dan urutkan
        $transactions = $withdrawals->concat($pointExchanges)->concat($wasteDeposits)->sortByDesc('created_at');

        // Jika tidak ada data, return kosong untuk AJAX menangani tampilan
        if ($transactions->isEmpty()) {
            return "";
        }

        // Return HTML langsung tanpa partial
        $html = "";
        foreach ($transactions as $transaction) {
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
                'approved' => 'badge badge-success',
                'rejected' => 'badge badge-danger',
                'pending' => 'badge badge-warning',
                default => 'badge bg-secondary',
            };

            $icon = $icons[$transaction->type] ?? 'default-icon.png';
            $title = $titles[$transaction->type] ?? 'Transaksi';

            $html .= "<div class='card p-1 mb-2 shadow-sm'>
            <div class='d-flex align-items-center'>
                <img src='$icon' alt='icon' class='me-3' width='40'>
                <div class='flex-grow-1'>
                    <h5 class='mb-1'>$title</h5>
                    <small class='text-muted d-block'>" . \Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y') . "</small>
                    <small class='$badgeClass'>" . ucfirst($transaction->status) . "</small>
                </div>
                <div class='text-end'>";

            if ($transaction->type === 'tarik_tunai') {
                $html .= "<small class='text-danger'>- Rp. " . number_format($transaction->amount, 0, ',', '.') . "</small>";
            } elseif ($transaction->type === 'setor_sampah') {
                $html .= "<small class='text-success'>+ Rp. " . number_format($transaction->total_amount, 0, ',', '.') . "</small>";
            } elseif ($transaction->type === 'tukar_points') {
                $html .= "<small class='d-block text-danger'>" . ($transaction->total_points > 0 ? '-' : '') . number_format($transaction->total_points, 0, ',', '.') . " poin</small>";
            }

            $html .= "  </div>
            </div>
        </div>";
        }

        return $html;
    }
}
