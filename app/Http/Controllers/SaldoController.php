<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Saldo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SaldoController extends Controller
{
    public function index(Request $request)
    {
        $title = "Data Transaksi";
        $breadcrumb = "Transaksi";
        if ($request->ajax()) {
            $data = Saldo::with('nasabah');
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

                ->addColumn('saldo_masuk', function ($data) {
                    return 'Rp ' . number_format($data->saldo_masuk, 0, ',', '.');
                })
                ->addColumn('saldo_keluar', function ($data) {
                    return 'Rp ' . number_format($data->saldo_keluar, 0, ',', '.');
                })
                ->addColumn('saldo_akhir', function ($data) {
                    return 'Rp ' . number_format($data->saldo_akhir, 0, ',', '.');
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
        $nasabahs = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'nasabah');
            })
            ->get();
        return view('dashboard.saldo.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'saldo_masuk' => 'nullable|numeric|min:0',
            'saldo_keluar' => 'nullable|numeric|min:0',
        ]);

        $saldoMasuk = (float) str_replace('.', '', $request->saldo_masuk ?? 0);
        $saldoKeluar = (float) str_replace('.', '', $request->saldo_keluar ?? 0);


        if ($saldoMasuk > 0 && $saldoKeluar == 0) {
            $saldoAkhir = $saldoMasuk;
        } else {
            $saldoAkhir = $saldoMasuk - $saldoKeluar;
        }

        Saldo::create([
            'user_id' => $request->user_id,
            'saldo_masuk' => $saldoMasuk,
            'saldo_keluar' => $saldoKeluar,
            'saldo_akhir' => $saldoAkhir,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Saldo berhasil disimpan!',
        ], 200);
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
            'saldo_masuk' => 'nullable|numeric|min:0',
            'saldo_keluar' => 'nullable|numeric|min:0',
        ]));
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        
        try {
DB::beginTransaction();
$saldo = Saldo::findOrFail($id);
            $saldoMasuk = (float) str_replace('.', '', $request->saldo_masuk ?? 0);
            $saldoKeluar = (float) str_replace('.', '', $request->saldo_keluar ?? 0);
            $saldoAkhir = $saldoMasuk - $saldoKeluar;

            $saldo->user_id = $request->user_id;
            $saldo->saldo_masuk = $saldoMasuk;
            $saldo->saldo_keluar = $saldoKeluar;
            $saldo->saldo_akhir = $saldoAkhir;
            $saldo->save();
Log::info(
    'Saldo berhasil diupdate',
    [
        'id' => $saldo->id,
        'user_id' => $saldo->user_id,
        'saldo_masuk' => $saldo->saldo_masuk,
        'saldo_keluar' => $saldo->saldo_keluar,
        'saldo_akhir' => $saldo->saldo_akhir,
        ]
    );
    DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data Saldo berhasil diupdate!',
                'saldo' =>$saldo
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
