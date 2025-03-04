<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Rewards;
use Illuminate\Http\Request;
use App\Models\PenukaranPoints;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PenukaranPoinController extends Controller
{
    public function index(Request $request)
    {
        $title = "Data Transaksi";
        $breadcrumb = "Transaksi";
        $nasabahs = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'nasabah');
            })
            ->get();

        $rewards = Rewards::all();
        if ($request->ajax()) {
            $data = PenukaranPoints::with(['user','reward']);
            if ($search = $request->input('search.value')) {
                $data->whereHas('users', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            }

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('user_id', function ($data) {
                    return $data->user->name;
                })
                ->addColumn('reward_id', function ($data) {
                    return $data->reward->name;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status === 'berhasil') {
                        return '<span class="badge badge-primary">' . $data->status . '</span>';
                    } elseif ($data->status === 'gagal') {
                        return '<span class="badge badge-danger">' . $data->status . '</span>';
                    } elseif ($data->status === 'pending') {
                        return '<span class="badge badge-warning">' . $data->status . '</span>';
                    } else {
                        return '<span class="badge badge-secondary">' . $data->status . '</span>';
                    }
                })


                ->addColumn('action', function ($data) {
                    $buttons = '<div class="text-center">';
                    //Check permission for adding/editing permissions
                    if (Gate::allows('update rewards')) {
                        $buttons .= '<a href="' . route('rewards.edit', $data->id) . '" class="btn btn-sm btn-primary mr-1">
                        <i class="fas fa-edit"></i>
                     </a>';
                    }
                    // Check permission for deleting permissions
                    if (Gate::allows('delete rewards')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-danger mr-1 delete-button" data-id="' . $data->id . '" data-section="rewards">' .
                            '<i class="fas fa-trash-alt"></i> 
                                     </button>';
                    }
                    if (Gate::allows('read rewards')) {
                        $buttons .= '<a href="' . route('rewards.show', $data->id) . '" class="btn btn-sm btn-info btn-show-user">
                    <i class="fas fa-eye"></i>
                 </a>';
                    }
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('dashboard.penukaranPoints.index', get_defined_vars());
    }

    public function create()
    {
        $nasabahs = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'nasabah');
            })
            ->get();

        $rewards = Rewards::all();
        return view('dashboard.penukaranPoints.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'reward_id' => 'required|exists:rewards,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            $reward = Rewards::findOrFail($request->reward_id);
            if ($user->points < $reward->points) {
                return response()->json([
                    'success' => false,
                    'error' => 'Saldo poin anda tidak cukup'
                ], 400);
            }
        
            $user->points -= $reward->points;
            $user->save(); 
        
            PenukaranPoints::create([
                'user_id' => $user->id,
                'reward_id' => $request->reward_id,
                'total_points' => $reward->points,
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Penukaran poin berhasil',
                'data' => $user
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat melakukan penukaran poin',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
