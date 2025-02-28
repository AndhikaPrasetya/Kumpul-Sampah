<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
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

    public function getTransactionDetail(Request $request)  {
        {
            $title = "Data Transaksi";
            $breadcrumb = "Transaksi";
            if ($request->ajax()) {
                $data = TransactionDetail::with(['transaction.users','sampah']);
                if ($search = $request->input('search.value')) {
                    $data->where(function ($data) use ($search) {
                        $data->where('sampah_id', 'like', "%{$search}%");
                    });
                }
    
                return DataTables::eloquent($data)
                    ->addIndexColumn()
                    ->addColumn('transaction_id', function ($data) {
                        return $data->transaction->users->name;
                    })
                    ->addColumn('sampah_id', function ($data) {
                        return $data->sampah->nama;
                    })
                    ->addColumn('berat', function ($data) {
                        return number_format($data->berat, 0, ',', '.').'KG ';
                     
                    })
                    ->addColumn('subtotal', function ($data) {
                        return 'Rp ' . number_format($data->subtotal, 0, ',', '.');
                    })
                    ->addColumn('created_at', function ($data) {
                        return Carbon::parse($data->created_at)->format('d-m-Y');
                    })
                    ->make(true);
            }
            return view('dashboard.transaction.historyTransaksi', get_defined_vars());
        }
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
        $user = User::findOrFail($request->user_id);
        // Simpan transaksi utama
        $transaction = Transactions::create([
            'user_id' => $request->user_id,
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'total_amount' => 0, // Akan dihitung setelah menyimpan detail
            'status' => 'pending'
        ]);
    
        $totalAmount = 0;
        $transactionDetails = [];
    
        // Pre-fetch semua data sampah sekaligus
        $sampahIds = $request->sampah_id;
        $sampahItems = Sampah::whereIn('id', $sampahIds)->get()->keyBy('id');
        
        // Gunakan timestamp yang sama untuk semua record
        $now = now();
    
        foreach ($request->sampah_id as $key => $sampahId) {
            // Ambil harga sampah dari collection yang sudah di-cache
            $sampah = $sampahItems[$sampahId];
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
                'created_at' => $now,
                'updated_at' => $now
            ];
    
            // Tambahkan subtotal ke total amount
            $totalAmount += $subtotal;
        }
    
        // Simpan semua transaction details sekaligus
        TransactionDetail::insert($transactionDetails);
    
        // Update total_amount di transactions
        $transaction->update(['total_amount' => $totalAmount]);

        // Get the latest saldo record for this user
        $saldoNasabah = Saldo::where('user_id', $user->id)->first();

        if($saldoNasabah){
            $saldoNasabah->update([
                'balance'=>$saldoNasabah->balance + $totalAmount,
            ]);
        } else{
            $saldoNasabah = Saldo::create([
                'user_id' => $user->id,
                'balance' => $totalAmount,
            ]);
        }
    
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

public function edit($id){
    $transaction = Transactions::find($id);
  
    if(!$transaction){
        return response()->json([
            'success' => false,
            'message' => 'Transaksi tidak ditemukan',
            ], 404);
    }
    //filter user
    $users = User::with('roles')
    ->whereHas('roles', function ($query) {
        $query->where('name', 'nasabah');
    })
    ->get();

    $sampahs = Sampah::all();
    $transactionDetails = TransactionDetail::where('transaction_id',$id)->get();
    $selectedUserIds = $transaction->user_id;
    
   return view('dashboard.transaction.edit', get_defined_vars());

}
public function show($id){
    $transaction = Transactions::find($id);
  
    if(!$transaction){
        return response()->json([
            'success' => false,
            'message' => 'Transaksi tidak ditemukan',
            ], 404);
    }
    //filter user
    $users = User::with('roles')
    ->whereHas('roles', function ($query) {
        $query->where('name', 'nasabah');
    })
    ->get();

    $sampahs = Sampah::all();
    $transactionDetails = TransactionDetail::where('transaction_id',$id)->get();
    $selectedUserIds = $transaction->user_id;
    
   return view('dashboard.transaction.detail', get_defined_vars());

}

public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
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

        // Cek apakah transaksi ada
        $transaction = Transactions::findOrFail($id);
        
        // Hapus detail transaksi lama
        TransactionDetail::where('transaction_id', $id)->delete();
        
        $totalAmount = 0;
        $transactionDetails = [];
        
        // Pre-fetch semua data sampah sekaligus
        $sampahIds = $request->sampah_id;
        $sampahItems = Sampah::whereIn('id', $sampahIds)->get()->keyBy('id');
        
        // Gunakan timestamp yang sama untuk semua record
        $now = now();

        foreach ($request->sampah_id as $key => $sampahId) {
            // Ambil harga sampah dari collection yang sudah di-cache
            $sampah = $sampahItems[$sampahId];
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
                'created_at' => $now,
                'updated_at' => $now
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
            'message' => 'Transaksi berhasil diperbarui',
            'data' => $transaction
        ], 200);
    } catch (Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat memperbarui transaksi.',
            'errors' => $e->getMessage()
        ], 500);
    }
}

public function deleteTransactionDetail($id){
    $transaction =TransactionDetail::findOrFail($id);
    if($transaction){
        $transaction->delete();
        return response()->json([
            'success' => true,
            'message' => 'Detail transaksi berhasil dihapus',
            'data' => $transaction
            ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Detail transaksi tidak ditemukan',
                    'errors' => 'Detail transaksi tidak ditemukan'
                    ], 404);
    }
}

public function destroy($id){
    $transaction = Transactions::find($id);
    if (!$transaction) {
        return response()->json([
            'success' => false,
            'message' => 'Transaksi tidak ditemukan',
            ], 404);
    }
    $transaction->delete();
    return response()->json([
        'success' => true,
        'message' => 'Transaksi berhasil dihapus',
        ], 200);
        
}
    
}
