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

class SetorSampahController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $nasabahDetail = NasabahDetail::where('user_id', $user->id)->first();
    
        // Jika nasabahDetail tidak ditemukan, set bsu_id ke null
        $bsuId = $nasabahDetail ? $nasabahDetail->bsu_id : null;
        $sampahs = Sampah::with('categories')->where('bsu_id', $bsuId)->get();
        $kategoriSampah =CategorySampah::where('bsu_id', $bsuId)->get();

    
        return view('frontend.setor-sampah.list', compact('sampahs','kategoriSampah'));
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
}
