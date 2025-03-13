<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Saldo;
use App\Models\Sampah;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\NasabahDetail;
use Illuminate\Support\Carbon;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $title = "Data Transaksi";
        $breadcrumb = "Transaksi";
        $bsuId = $request->user()->id;
        $nasabahs = $this->getNasabahUsers($bsuId);
        if ($request->ajax()) {
            //cara agar ascending
            $data = Transactions::with(['users','details'])->where('bsu_id', $request->user()->id)->orderBy('created_at', 'desc');
            if ($search = $request->input('search.value')) {
                $data->whereHas('users', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                    ->orWhere('tanggal', 'like', "%{$search}%");
            }

            $data->when($request->filled('nama_nasabah_transaksi'), function ($query) use ($request) {
                $query->whereHas('users', function ($subQuery) use ($request) {
                    $subQuery->whereIn('name', $request->nama_nasabah_transaksi);
                });
            })
                ->when($request->filled('start_date') && $request->filled('end_date'), function ($query) use ($request) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->end_date)->endOfDay()
                    ]);
                })
                ->when($request->filled('status_transaksi'), function ($query) use ($request) {
                    $query->whereIn('status', $request->status_transaksi);
                });

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('user_id', function ($data) {
                    return $data->users->name;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status === 'approved') {
                        return '<span class="badge badge-primary">' . $data->status . '</span>';
                    } elseif ($data->status === 'rejected') {
                        return '<span class="badge badge-danger">' . $data->status . '</span>';
                    } elseif ($data->status === 'pending') {
                        return '<span class="badge badge-warning">' . $data->status . '</span>';
                    } else {
                        return '<span class="badge badge-secondary">' . $data->status . '</span>';
                    }
                })
                ->addColumn('total_amount', function ($data) {
                    return 'Rp ' . number_format($data->total_amount, 0, ',', '.');
                })
                ->addColumn('total_points', function ($data) {
                    return number_format($data->total_points, 0, ',', '.');
                })
                //cara sum transaction->details->berat 
                ->addColumn('berat', function ($data) {
                    return number_format($data->details->sum('berat'), 0, ',', '.'). 'KG';
                })
                ->addColumn('tanggal', function ($data) {
                    return Carbon::parse($data->tanggal)->format('d-m-Y');
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="text-center">';
                    //Check permission for adding/editing permissions
                    if (Gate::allows('update transaction')) {
                        $buttons .= '<a href="' . route('transaction.edit', $data->id) . '" class="btn btn-sm btn-primary mr-1">
                        <i class="fas fa-edit"></i>
                     </a>';
                    }
                    // Check permission for deleting permissions
                    if (Gate::allows('delete transaction')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-danger mr-1 delete-button" data-id="' . $data->id . '" data-section="transaction">' .
                            '<i class="fas fa-trash-alt"></i> 
                                     </button>';
                    }
                    if (Gate::allows('read transaction')) {
                        $buttons .= '<a href="' . route('transaction.show', $data->id) . '" class="btn btn-sm btn-info btn-show-user">
                    <i class="fas fa-eye"></i>
                 </a>';
                    }
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('dashboard.transaction.index', get_defined_vars());
    }

    public function getTransactionDetail(Request $request)
    {

        $title = "Data Riwayat Transaksi";
        $breadcrumb = "Riwayat Transaksi";
        $bsuId = $request->user()->id;
        $nasabahs = $this->getNasabahUsers($bsuId);
        if ($request->ajax()) {
            $data = TransactionDetail::with(['transaction.users', 'sampah'])
            ->whereHas('transaction', function ($query) use($request) {
                $query->where('bsu_id', $request->user()->id);
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
                    $query->whereBetween('created_at', [
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
                ->addColumn('status', function ($data) {
                    if ($data->transaction->status === 'approved') {
                        return '<span class="badge badge-primary">' . $data->transaction->status . '</span>';
                    } elseif ($data->transaction->status === 'rejected') {
                        return '<span class="badge badge-danger">' . $data->transaction->status . '</span>';
                    } elseif ($data->transaction->status === 'pending') {
                        return '<span class="badge badge-warning">' . $data->transaction->status . '</span>';
                    } else {
                        return '<span class="badge badge-secondary">' . $data->transaction->status . '</span>';
                    }
                })
                ->addColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->format('d-m-Y');
                })
                ->rawColumns(['status'])
                ->make(true);
        }
        return view('dashboard.transaction.historyTransaksi', get_defined_vars());
    }

    public function create()
    {
        $currentUserId = optional(Auth::user())->id; 

        // Ambil nasabah yang terkait dengan BSU saat ini
        $users = $this->getNasabahUsers($currentUserId);
        //data sampah
        $sampahs = $this->getSampahData($currentUserId);

        return view('dashboard.transaction.create', compact('users', 'sampahs'));
    }

    public function store(Request $request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
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
                'message' => 'Transaksi berhasil disimpan',
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
        return Transactions::create([
            'user_id' => $request->user_id,
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'total_amount' => 0,
            'total_points' => 0,
            'status' => 'pending',
            'bsu_id' => $request->user()->id,
        ]);
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
    }


    public function show(Request $request, $id)
    {
        $currentUserId = $request->user()->id;

        // Cari transaksi dengan chaining where dan findOrFail
        $transaction = Transactions::where('bsu_id', $currentUserId)->findOrFail($id);

        // Tidak perlu cek null karena findOrFail sudah throw exception jika tidak ditemukan
        // Yang akan ditangkap oleh Laravel dan otomatis mengembalikan 404

        // Load data terkait
        $users = $this->getNasabahUsers($currentUserId);
        $sampahs = $this->getSampahData($currentUserId);
        $transactionDetails = $this->getTransactionDetails($id);
        $selectedUserIds = $transaction->user_id;

        // Gunakan compact untuk eksplisit menyatakan variabel yang diteruskan ke view
        return view('dashboard.transaction.detail', compact(
            'transaction',
            'users',
            'sampahs',
            'transactionDetails',
            'selectedUserIds',
            'currentUserId'
        ));
    }
    public function edit(Request $request, $id)
    {
        $currentUserId = $request->user()->id;

        // Cari transaksi dengan chaining where dan findOrFail
        $transaction = Transactions::where('bsu_id', $currentUserId)->findOrFail($id);

        // Load data terkait
        $users = $this->getNasabahUsers($currentUserId);
        $sampahs = $this->getSampahData($currentUserId);
        $transactionDetails = $this->getTransactionDetails($id);
        $selectedUserIds = $transaction->user_id;

        return view('dashboard.transaction.edit', get_defined_vars());
    }

    /**
     * Get users with nasabah role that belong to current BSU
     *
     * @param int $bsuId
     * @return \Illuminate\Database\Eloquent\Collection
     */
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

    /**
     * Get sampah data for current BSU
     *
     * @param int $bsuId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getSampahData($bsuId)
    {
        return Sampah::where('bsu_id', $bsuId)->get();
    }

    /**
     * Get transaction details for a transaction
     *
     * @param int $transactionId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getTransactionDetails($transactionId)
    {
        return TransactionDetail::where('transaction_id', $transactionId)->get();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sampah_id' => 'required|array',
            'sampah_id.*' => 'required|exists:sampahs,id',
            'berat' => 'required|array',
            'berat.*' => 'required|numeric|min:0.1',
            'status' => 'sometimes|required|in:approved,rejected,pending'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            DB::beginTransaction();
    
            // Find transaction or fail with 404
            $transaction = Transactions::findOrFail($id);
            $bsu_id = $request->user()->id;
            $originalStatus = $transaction->status;
            $newStatus = $request->status ?? $originalStatus;
    
            // Process transaction details update
            $result = $this->processUpdateDetails($request, $transaction);
            $transaction = $this->updateTransactionTotals($transaction, $result);
            
            // Handle balance updates based on status change
            $this->handleStatusChange($transaction, $originalStatus, $newStatus);
            
            // Update transaction status if provided
            if ($request->has('status')) {
                $transaction->update(['status' => $newStatus]);
            }
             Cache::forget("total_sampah_{$bsu_id}");
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui',
                'data' => $transaction
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan',
            ], 404);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui transaksi.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Handle balance updates based on transaction status changes
     *
     * @param Transactions $transaction
     * @param string $originalStatus
     * @param string $newStatus
     * @return void
     */
    private function handleStatusChange(Transactions $transaction, string $originalStatus, string $newStatus): void
    {
        // Skip if status hasn't changed
        if ($originalStatus === $newStatus) {
            return;
        }
    
        $saldoNasabah = Saldo::where('user_id', $transaction->user_id)->first();
    
        // Case 1: Transaction was approved but now rejected - deduct balance
        if ($originalStatus === 'approved' && $newStatus === 'rejected') {
            $this->deductBalance($saldoNasabah, $transaction);
        }
    
        // Case 2: Transaction is newly approved - add balance
        if ($newStatus === 'approved' && $originalStatus !== 'approved') {
            $this->addBalance($saldoNasabah, $transaction);
        }
    }
    
    /**
     * Deduct transaction amount from user balance
     *
     * @param Saldo|null $saldoNasabah
     * @param Transactions $transaction
     * @return void
     */
    private function deductBalance(?Saldo $saldoNasabah, Transactions $transaction): void
    {
        $bsu_id= Auth::id();
        if ($saldoNasabah) {
            $saldoNasabah->update([
                'balance' => max(0, $saldoNasabah->balance - $transaction->total_amount),
                'points' => max(0, $saldoNasabah->points - $transaction->total_points),
            ]);
            Cache::forget("total_saldo_{$bsu_id}");
        }
    }
    
    /**
     * Add transaction amount to user balance
     *
     * @param Saldo|null $saldoNasabah
     * @param Transactions $transaction
     * @return void
     */
    private function addBalance(?Saldo $saldoNasabah, Transactions $transaction): void
    {
        $totalAmount = $transaction->total_amount;
        $totalPoints = $transaction->total_points;
        $bsu_id = Auth::id();
    
        if ($saldoNasabah) {
            $saldoNasabah->update([
                'balance' => $saldoNasabah->balance + $totalAmount,
                'points' => $saldoNasabah->points + $totalPoints,
            ]);
            Cache::forget("total_saldo_{$bsu_id}");
        } else {
            Saldo::create([
                'user_id' => $transaction->user_id,
                'balance' => $totalAmount,
                'points' => $totalPoints,
            ]);
        }
    }
    /**
     * Process updated transaction details
     *
     * @param Request $request
     * @param Transactions $transaction
     * @return array
     */
    private function processUpdateDetails(Request $request, Transactions $transaction)
    {
        $totalAmount = 0;
        $totalPoints = 0;
        $transactionDetails = [];
        $now = now();
        // Pre-fetch semua data sampah sekaligus
        $sampahIds = $request->sampah_id;
        $sampahItems = Sampah::whereIn('id', $sampahIds)->get()->keyBy('id');

        TransactionDetail::where('transaction_id', $transaction->id)->delete();

        foreach ($request->sampah_id as $key => $sampahId) {
            // Ambil harga sampah dari collection yang sudah di-cache
            $sampah = $sampahItems[$sampahId];
            $hargaPerKg = $sampah->harga;

            // Hitung subtotal
            $berat = $request->berat[$key];
            $subtotal = $berat * $hargaPerKg;
            $points = $berat * $sampah->points;

            // Simpan detail transaksi
            $transactionDetails[] = [
                'transaction_id' => $transaction->id,
                'sampah_id' => $sampahId,
                'berat' => $berat,
                'subtotal' => $subtotal,
                'points' => $points,
                'created_at' => $now,
                'updated_at' => $now
            ];

            // Tambahkan subtotal ke total amount
            $totalAmount += $subtotal;
            $totalPoints += $points;
        }
        // Simpan semua transaction details sekaligus
        TransactionDetail::insert($transactionDetails);
        return [
            'totalAmount' => $totalAmount,
            'totalPoints' => $totalPoints
        ];

    }

    /**
     * Update transaction with new totals
     *
     * @param Transactions $transaction
     * @param array $totals
     * @return Transactions
     */
    private function updateTransactionTotals(Transactions $transaction, array $totals)
    {
        $transaction->update([
            'total_amount' => $totals['totalAmount'],
            'total_points' => $totals['totalPoints']
        ]);

        return $transaction;
    }

    /**
     * Delete all existing transaction details
     *
     * @param int $transactionId
     * @return void
     */
    private function deleteTransactionOld($transactionId)
    {
        TransactionDetail::where('transaction_id', $transactionId)->delete();
    }

    // Private method to handle the approval functionality
    // private function handleApproval(Request $request, $id)
    // {
    //     $transaction = Transactions::find($id);
    //     if (!$transaction) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Transaksi tidak ditemukan',
    //         ], 404);
    //     }

    //     if ($transaction->status !== 'pending') {
    //         return response()->json([
    //             'success' => false,
    //             'error' => 'Transaksi sudah disetujui',
    //         ], 400);
    //     }

    //     $transaction->update(['status' => $request->status]);

    //     if ($request->status === 'approved') {
    //         // Get the user and total amount
    //         $user = User::find($transaction->user_id);
    //         $totalAmount = $transaction->total_amount;
    //         $totalPoints = $transaction->total_points;

    //         // Get the latest saldo record for this user
    //         $saldoNasabah = Saldo::where('user_id', $user->id)->first();

    //         if ($saldoNasabah) {
    //             $saldoNasabah->update([
    //                 'balance' => $saldoNasabah->balance + $totalAmount,
    //                 'points' => $saldoNasabah->points + $totalPoints,
    //             ]);
    //         } else {
    //             $saldoNasabah = Saldo::create([
    //                 'user_id' => $user->id,
    //                 'balance' => $totalAmount,
    //             ]);
    //         }
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Status transaksi berhasil diubah',
    //         'data' => $transaction
    //     ], 200);
    // }
    public function deleteTransactionDetail($id)
    {
        $transactionDetail = TransactionDetail::findOrFail($id);

        // Simpan ID transaksi sebelum dihapus
        $transactionId = $transactionDetail->transaction_id;

        if ($transactionDetail) {
            $transactionDetail->delete();

            // Update total_amount di tabel transactions
            $this->updateTotalAmount($transactionId);

            return response()->json([
                'success' => true,
                'message' => 'Detail transaksi berhasil dihapus'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Detail transaksi tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Fungsi untuk mengupdate total_amount di tabel transactions
     *
     * @param int $transactionId
     * @return void
     */
    private function updateTotalAmount($transactionId)
    {
        $transaction = Transactions::find($transactionId);

        if ($transaction) {
            // Ambil total subtotal dan points dalam satu query
            $totals = TransactionDetail::where('transaction_id', $transactionId)
                ->selectRaw('SUM(subtotal) as total_amount, SUM(points) as total_point')
                ->first();

            // Update total_amount dan points di transaksi

            $transaction->update([
                'total_amount' => $totals->total_amount ?? 0, // Jika NULL, jadikan 0
                'total_points' => $totals->total_point ?? 0
            ]);
        }
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();
    
            // Cek apakah transaksi ada
            $transaction = Transactions::find($id);
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan',
                ], 404);
            }
    
            // Ambil saldo pengguna
            $saldoNasabah = Saldo::where('user_id', $transaction->user_id)->first();
    
            if ($saldoNasabah) {
                // Kurangi saldo dan poin sesuai transaksi yang dihapus
                $saldoNasabah->update([
                    'balance' => max(0, $saldoNasabah->balance - $transaction->total_amount),
                    'points' => max(0, $saldoNasabah->points - $transaction->total_points),
                ]);
            }
    
            // Hapus transaksi
            $transaction->delete();
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus dan saldo diperbarui.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus transaksi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
