<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Sampah;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\NasabahDetail;
use App\Models\CategorySampah;
use Illuminate\Support\Carbon;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

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

    public function setorSampah(){
        $user = Auth::user();
        $nasabahDetail = NasabahDetail::where('user_id', $user->id)->first();
        $bsuId = $nasabahDetail ? $nasabahDetail->bsu_id : null;
        
        // Ambil semua kategori sampah
        $kategoriSampah = CategorySampah::where('bsu_id', $bsuId)->get();
        
        // Ambil semua sampah yang terkait dengan BSU
        $sampahs = Sampah::with('categories')->where('bsu_id', $bsuId)->get();
        
        // Kelompokkan sampah berdasarkan category_id
        $groupedSampahs = [];
        foreach ($sampahs as $sampah) {
            $categoryId = $sampah->categories->id; // Asumsikan relasi categories ada
            if (!isset($groupedSampahs[$categoryId])) {
                $groupedSampahs[$categoryId] = [];
            }
            $groupedSampahs[$categoryId][] = $sampah;
        }
        return view('frontend.transaction.setor', compact('kategoriSampah', 'groupedSampahs'));
    }
    public function store(Request $request)
    {
        $rules = [
            'sampah_id' => 'required|array',
            'sampah_id.*' => 'required|exists:sampahs,id',
            'berat' => 'required|array',
            'berat.*' => 'required|numeric|min:0.1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create transaction with initial values

            $transaction = $this->createTransaction($request);
            // Process transaction details and calculate totals
            $result = $this->processTransactionDetails($request, $transaction);

            // Update transaction with final totals
            $transaction->update([
                'total_amount' => $result['totalAmount'],
                'total_points' => $result['totalPoints']
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil setor sampah',
                'data' => $transaction
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan transaksi.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new transaction record
     *
     * @param Request $request
     * @return Transactions
     */
    private function createTransaction(Request $request)
    {
        // Create a unique lock key based on the user ID to prevent race conditions
        $lockKey = "create_transaction_user_{$request->user_id}";
        $user = Auth::user();
        $nasabahDetail = NasabahDetail::where('user_id', $user->id)->first();
    
        // Jika nasabahDetail tidak ditemukan, set bsu_id ke null
        $bsuId = $nasabahDetail ? $nasabahDetail->bsu_id : null;
        
        return Cache::lock($lockKey, 10)->block(5, function() use ($request,$user,$bsuId) {
            return Transactions::create([
                'user_id' => $user->id,
                'tanggal' => Carbon::now()->format('Y-m-d'),
                'total_amount' => 0,
                'total_points' => 0,
                'status' => 'pending',
                'bsu_id' => $bsuId,
            ]);
        });
    }

    /**
     * Process transaction details and calculate totals
     *
     * @param Request $request
     * @param Transactions $transaction
     * @return array
     */
    private function processTransactionDetails(Request $request, Transactions $transaction)
    {
        // Use a cache lock with a unique key based on the transaction ID
        $lockKey = "transaction_processing_{$transaction->id}";
        
        return Cache::lock($lockKey, 10)->block(5, function() use ($request, $transaction) {
            $totalAmount = 0;
            $totalPoints = 0;
            $transactionDetails = [];
            $now = now();
    
            // Pre-fetch all sampah data at once
            $sampahItems = Sampah::whereIn('id', $request->sampah_id)
                ->get()
                ->keyBy('id');
    
            foreach ($request->sampah_id as $key => $sampahId) {
                $sampah = $sampahItems[$sampahId];
                $berat = $request->berat[$key];
    
                // Calculate values
                $subtotal = $berat * $sampah->harga;
                $points = $berat * $sampah->points;
    
                // Build transaction detail record
                $transactionDetails[] = [
                    'transaction_id' => $transaction->id,
                    'sampah_id' => $sampahId,
                    'berat' => $berat,
                    'subtotal' => $subtotal,
                    'points' => $points,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
    
                // Update running totals
                $totalAmount += $subtotal;
                $totalPoints += $points;
            }
    
            // Bulk insert all transaction details
            TransactionDetail::insert($transactionDetails);
    
            return [
                'totalAmount' => $totalAmount,
                'totalPoints' => $totalPoints
            ];
        });
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
            <a href='" . route('transaction-details', $transaction->id) . "' class='text-decoration-none text-dark'>
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

    public function listSampah(){
        $user = Auth::user();
        $nasabahDetail = NasabahDetail::where('user_id', $user->id)->first();
        $bsuId = $nasabahDetail ? $nasabahDetail->bsu_id : null;
        // Ambil semua sampah yang terkait dengan BSU
        $sampahs = Sampah::with('categories')->where('bsu_id', $bsuId)->get();
        
        return view('frontend.sampah.list',compact('sampahs'));
    }

    public function transactionDetails($id){
        $transaction = Transactions::with('details.sampah')->findOrFail($id);
        $transactionDetail = $transaction->details;
        $transactionCode = $transaction->transaction_code;
        $transactionDate =Carbon::parse($transaction->tanggal)->format('d-m-y');
     
      

        return view('frontend.transaction.detail',compact('transactionDetail','transactionCode','transactionDate','transactionDate','transactionDate','transactionDate'));
    }
    
}
