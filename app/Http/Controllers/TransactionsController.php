<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Sampah;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $title = "Data Transaksi";
        $breadcrumb = "Transaksi";
        if ($request->ajax()) {
            $data = Transactions::with('users');
            if ($search = $request->input('search.value')) {
                $data->where(function ($data) use ($search) {
                    $data->where('user_id', 'like', "%{$search}%");
                });
            }

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('user_id', function ($data) {
                    return $data->users->name;
                })
                ->addColumn('tanggal', function ($data) {
                    return Carbon::parse($data->tanggal)->format('d-m-Y');
                })
                ->addColumn('total_amount', function ($data) {
                    return 'Rp ' . number_format($data->total_amount, 0, ',', '.');
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
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.transaction.index', get_defined_vars());
    }

    public function create()
    {
        $users = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'nasabah');
            })
            ->get();
        $sampahs = Sampah::all();
        return view('dashboard.transaction.create', get_defined_vars());
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'sampah_id' => 'required|array',
        'sampah_id.*' => 'required|exists:sampahs,id',
        'berat' => 'required|array',
        'berat.*' => 'required|numeric|min:0.1',
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

        // Simpan transaksi utama
        $transaction = Transactions::create([
            'user_id' => $request->user_id,
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'total_amount' => 0, // Akan dihitung setelah menyimpan detail
        ]);

        $totalAmount = 0;
        $transactionDetails = [];

        foreach ($request->sampah_id as $key => $sampahId) {
            // Ambil harga sampah dari database
            $sampah = Sampah::find($sampahId);
            $hargaPerKg = $sampah->harga;

            // Hitung subtotal
            $berat = $request->berat[$key];
            $subtotal = $berat * $hargaPerKg;

            // Simpan detail transaksi
            $transactionDetails[] = [
                'transaction_id' => $transaction->id,
                'sampah_id' => $sampahId,
                'berat' => $berat,
                'subtotal' => $subtotal,
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Tambahkan subtotal ke total amount
            $totalAmount += $subtotal;
        }

        // Simpan semua transaction details sekaligus
        TransactionDetail::insert($transactionDetails);

        // Update total_amount di transactions
        $transaction->update(['total_amount' => $totalAmount]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan',
            'data' => $transaction
        ], 201);
    } catch (Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat menyimpan transaksi.',
            'errors' => $e->getMessage()
        ], 500);
    }
}
    
}
