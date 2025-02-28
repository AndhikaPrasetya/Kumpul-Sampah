<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use Exception;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Data Penarikan";
        $breadcrumb = "Penarikan";
        if ($request->ajax()) {
            $data = Withdraw::with('user');
            if ($search = $request->input('search.value')) {
                $data->where(function ($data) use ($search) {
                    $data->where('user_id', 'like', "%{$search}%");
                });
            }

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('user_id', function ($data) {
                    return $data->user->name;
                })

                ->addColumn('amount', function ($data) {
                    return 'Rp ' . number_format($data->amount, 0, ',', '.');
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
                ->addColumn('tanggal', function ($data) {
                    return Carbon::parse($data->tanggal)->format('d-m-y');
                })
                
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="text-center">';
                    //Check permission for adding/editing permissions
                    if (Gate::allows('update withdraw')) {
                        $buttons .= '<a href="' . route('withdraw.edit', $data->id) . '" class="btn btn-sm btn-primary mr-1">
                        <i class="fas fa-edit"></i>
                     </a>';
                    }
                    // Check permission for deleting permissions
                    if (Gate::allows('delete withdraw')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-danger mr-1 delete-button" data-id="' . $data->id . '" data-section="withdraw">' .
                            '<i class="fas fa-trash-alt"></i> 
                                     </button>';
                    }
                    if (Gate::allows('read withdraw')) {
                        $buttons .= '<a href="' . route('withdraw.show', $data->id) . '" class="btn btn-sm btn-info btn-show-user">
                    <i class="fas fa-eye"></i>
                 </a>';
                    }
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }
        return view('dashboard.withdraw.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nasabahs = User::with('roles')
        ->whereHas('roles', function ($query) {
            $query->where('name', 'nasabah');
        })
        ->get();
        return view('dashboard.withdraw.create', get_defined_vars());
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
             'user_id' => 'required|exists:users,id',
        'amount' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try{
            DB::beginTransaction();
            $saldo = Saldo::where('user_id', $request->user_id)->first();

            if(!$saldo || $saldo->balance < $request->amount){
                return response()->json(['error' => 'Saldo tidak cukup'], 400);
            }

             // Simpan data ke tabel withdrawals
        Withdraw::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'tanggal' => now(),
            'status' => 'pending'
        ]);
        DB::commit();
        return response()->json(['message' => 'Permintaan penarikan berhasil, menunggu persetujuan'], 200);

        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan penarikan',
                'errors' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Withdraw::findOrFail($id);
        $nasabahs = User::with('roles')
        ->whereHas('roles', function ($query) {
            $query->where('name', 'nasabah');
        })
        ->get();
        return view('dashboard.withdraw.detail', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Withdraw::findOrFail($id);
        $nasabahs = User::with('roles')
        ->whereHas('roles', function ($query) {
            $query->where('name', 'nasabah');
        })
        ->get();
        return view('dashboard.withdraw.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function approveWithdraw(Request $request, $id)
{

    $withdraw = Withdraw::findOrFail($id);

    if ($withdraw->status !== 'pending') {
        return response()->json(['error' => 'Permintaan sudah diproses'], 400);
    }

    // Simpan gambar ke storage
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('withdrawals', 'public'); // Simpan ke storage/app/public/withdrawals
        $withdraw->image = $imagePath;
    }

    // Update status menjadi approved
    $withdraw->status = 'approved';
    $withdraw->save();

    // Kurangi saldo user
    $saldo = Saldo::where('user_id', $withdraw->user_id)->first();
    if ($saldo && $saldo->balance >= $withdraw->amount) {
        $saldo->balance -= $withdraw->amount;
        $saldo->save();
    } else {
        return response()->json(['error' => 'Saldo tidak cukup'], 400);
    }

    return response()->json(['message' => 'Penarikan berhasil disetujui dan bukti transfer diunggah'], 200);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Withdraw::findOrFail($id);
        if($data){
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'berhasil menghapus data',
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'gagal menghapus data',
            ],404);
        }
    }
}
