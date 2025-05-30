<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Saldo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SaldoController extends Controller
{
    public function index(Request $request)
    {
        $title = "Data Saldo";
        $breadcrumb = "Saldo";
        if ($request->ajax()) {
            $data = Saldo::with('nasabah')->where('bsu_id',$request->user()->id);
            if ($search = $request->input('search.value')) {
                $data->where(function ($data) use ($search) {
                    $data->where('user_id', 'like', "%{$search}%");
                });
            }

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('user_id', function ($data) {
                    return $data->nasabah->name;
                })

                ->addColumn('balance', function ($data) {
                    return 'Rp ' . number_format($data->balance, 0, ',', '.');
                })
                ->addColumn('points', function ($data) {
                    return number_format($data->points, 0, ',', '.');
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="text-center">';
                    //Check permission for adding/editing permissions
                    if (Gate::allows('update saldo')) {
                        $buttons .= '<a href="' . route('saldo.edit', $data->id) . '" class="btn btn-sm btn-primary mr-1">
                        <i class="fas fa-edit"></i>
                     </a>';
                    }
                    // Check permission for deleting permissions
                    if (Gate::allows('delete saldo')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-danger mr-1 delete-button" data-id="' . $data->id . '" data-section="saldo">' .
                            '<i class="fas fa-trash-alt"></i> 
                                     </button>';
                    }
                    if (Gate::allows('read saldo')) {
                        $buttons .= '<a href="' . route('saldo.show', $data->id) . '" class="btn btn-sm btn-info btn-show-user">
                    <i class="fas fa-eye"></i>
                 </a>';
                    }
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.saldo.index', get_defined_vars());
    }

    public function create()
    {
        $bsuId = Auth::user()->id;
        $nasabahs =$this->getNasabahUsers($bsuId);
        return view('dashboard.saldo.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'balance' => 'nullable|numeric|min:0',
            'points' => 'nullable|numeric|min:0',
        ]);

        $saldoMasuk = (float) str_replace('.', '', $request->balance ?? 0);
        $saldoPoints = (float) str_replace('.', '', $request->points ?? 0);

        Saldo::create([
            'user_id' => $request->user_id,
            'bsu_id' => $request->user()->id,
            'balance' => $saldoMasuk,
            'points' => $saldoPoints,
        ]);
      

        return response()->json([
            'success' => true,
            'message' => 'Data Saldo berhasil disimpan!',
        ], 200);
    }

    public function show($id)
    {
        $saldo = Saldo::findOrFail($id);
        $nasabahs = User::all();
        return view('dashboard.saldo.detail', get_defined_vars());
    }

    public function edit($id)
    {
        $saldo = Saldo::findOrFail($id);
        $nasabahs = User::all();
        return view('dashboard.saldo.edit', get_defined_vars());
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), ([
            'balance' => 'nullable|numeric|min:0',
            'points' => 'nullable|numeric|min:0',
        ]));
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            $saldo = Saldo::findOrFail($id);
            $saldoMasuk = (float) str_replace('.', '', $request->balance ?? 0);
            $saldoPoints = (float) str_replace('.', '', $request->points ?? 0);

            $saldo->user_id = $request->user_id;
            $saldo->balance = $saldoMasuk;
            $saldo->points = $saldoPoints;
            $saldo->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data Saldo berhasil diupdate!',
                'saldo' => $saldo
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Data Saldo gagal diupdate!',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

     /**
     * Get users with nasabah role that belong to current BSU
     *
     * @param int $bsuId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getNasabahUsers($bsuId){
        return User::with('roles')
        ->whereHas('roles', function ($query) {
            $query->where('name', 'nasabah');
        })
        ->whereHas('nasabahs', function ($query) use ($bsuId) {
            $query->where('bsu_id', $bsuId);
        })
        ->get();
    }

    public function destroy($id)
    {
        $data = Saldo::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Saldo berhasil dihapus!',
        ], 200);
    }
}
