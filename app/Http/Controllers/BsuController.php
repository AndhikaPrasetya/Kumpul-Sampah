<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\BsuDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;


class BsuController extends Controller
{
    public function index(Request $request)
    {
        $title = "Data bsu";
        $breadcrumb = "bsu";
        if ($request->ajax()) {
            $data = User::role('bsu');
            if ($search = $request->input('search.value')) {
                $data->where(function ($data) use ($search) {
                    $data->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('email', function ($data) {
                    return $data->email;
                })
                ->addColumn('no_phone', function ($data) {
                    return $data->no_phone;
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="text-center">';

                    if (Gate::allows('update bsu')) {
                        $buttons .= '<a href="' . route('bsu.edit', $data->id) . '" class="btn btn-sm btn-primary mr-1">
                                        <i class="fas fa-edit"></i>
                                     </a>';
                    }
                    
                    if (Gate::allows('delete bsu')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-danger mr-1 delete-button" data-id="' . $data->id . '" data-section="bsu">'.
                                    '<i class="fas fa-trash-alt"></i>
                                     </button>';
                    }
                    
                    if (Gate::allows('read bsu')) {
                        $buttons .= '<a href="' . route('bsu.show', $data->id) . '" class="btn btn-sm btn-info btn-show-user">
                    <i class="fas fa-eye"></i>
                 </a>';
                    }
                    
                    $buttons .= '</div>';
                    

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.bsu.index', get_defined_vars());
    }

    public function create(){
        return view('dashboard.bsu.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' =>'required|string|max:255',
            'email' =>'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'no_phone' =>'required|string|max:15',
            'rt' =>'required|string|max:15',
            'rw' =>'required|string|max:15',
            'kelurahan' =>'required|string|max:15',
            'alamat' =>'required|string|max:255',
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
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password =  Hash::make($request->password);
            $user->no_phone = $request->no_phone;
            $user->save();
            $user->assignRole('bsu');

            $bsuDetail =new BsuDetail();
            $bsuDetail->user_id = $user->id;
            $bsuDetail->rt = $request->rt;
            $bsuDetail->rw = $request->rw;
            $bsuDetail->kelurahan = $request->kelurahan;
            $bsuDetail->alamat = $request->alamat;
            $bsuDetail->save();
            DB::commit();
            return response()->json([
               'status' => true,
               'message' => 'bsu added successfully'
            ],200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
               'status' => false,
               'message' => 'Failed to add bsu',
               'error' => $e->getMessage()
            ],500);
        }
    }

    public function show($id){
        $bsu = User::role('bsu')->findOrFail($id);
        if (!$bsu) {
            return response()->json([
               'status' => false,
               'message' => 'bsu not found',
            ], 404);
        }
        $bsuDetail = BsuDetail::where('user_id', $id)->first();
        return view('dashboard.bsu.view', compact('bsu', 'bsuDetail'));
    }
    public function edit($id){
        $bsu = User::role('bsu')->findOrFail($id);
        if (!$bsu) {
            return response()->json([
               'status' => false,
               'message' => 'bsu not found',
            ], 404);
        }
        $bsuDetail = BsuDetail::where('user_id', $id)->first();
        return view('dashboard.bsu.edit', compact('bsu', 'bsuDetail'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'no_phone' => 'nullable|string|max:255',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|string|min:8',
            'alamat' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $user = User::find($id);
            $user->name = $request->name;
            $user->no_phone = $request->no_phone;

            // Update password jika diisi
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }
            $user->save();

            $bsuDetail = BsuDetail::where('user_id', $id)->first();
            $bsuDetail->rt = $request->rt;
            $bsuDetail->rw = $request->rw;
            $bsuDetail->kelurahan = $request->kelurahan;
            $bsuDetail->alamat = $request->alamat;
            $bsuDetail->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'bsu updated successfully',
            ], 200);
        } catch (Exception $e) {
            // Log::error('error',[$e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error update bsu',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id){
        try {
            DB::beginTransaction();
        
            $bsu = User::role('bsu')->findOrFail($id);
            $bsu->roles()->detach(); // Hapus relasi roles
            $bsu->delete(); 
        
            DB::commit();
            return response()->json([
               'status' => true,
               'message' => 'bsu deleted successfully'
            ],200);
        } catch(\Exception $e){
            DB::rollback();
            return response()->json([
               'status' => false,
               'message' => 'Failed to delete bsu',
               'error' => $e->getMessage()
            ],500);
        }
    }

}
