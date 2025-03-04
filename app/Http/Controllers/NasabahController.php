<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class NasabahController extends Controller
{
    public function index(Request $request)
    {
        $title = "Data Nasabah";
        $breadcrumb = "Nasabah";
        if ($request->ajax()) {
            $data = User::role('nasabah');
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

                    if (Gate::allows('update nasabah')) {
                        $buttons .= '<a href="' . route('nasabah.edit', $data->id) . '" class="btn btn-sm btn-primary mr-1">
                                        <i class="fas fa-edit"></i>
                                     </a>';
                    }
                    
                    if (Gate::allows('delete nasabah')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-danger mr-1 delete-button" data-id="' . $data->id . '" data-section="nasabah">'.
                                    '<i class="fas fa-trash-alt"></i>
                                     </button>';
                    }
                    
                    if (Gate::allows('read nasabah')) {
                        $buttons .= '<a href="' . route('nasabah.show', $data->id) . '" class="btn btn-sm btn-info btn-show-user">
                    <i class="fas fa-eye"></i>
                 </a>';
                    }
                    
                    $buttons .= '</div>';
                    

                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.nasabah.index', get_defined_vars());
    }

    public function create(){
        return view('dashboard.nasabah.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' =>'required|string|max:255',
            'email' =>'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'no_phone' =>'required|string|max:15',
            'alamat' =>'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $photo = $request->file('photo');
        $fileName = time() . '.' . $photo->getClientOriginalExtension();
        $photo->storeAs('foto-profile', $fileName, 'public');

        $data =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_phone' => $request->no_phone,
            'password' => Hash::make($request->password),
            'photo' => 'storage/foto-profile/' . $fileName,
        ]);
        //assign role nasabah
        $data->assignRole('nasabah');

        return response()->json([
           'status' => true,
           'message' => 'Data berhasil disimpan',
            'data' => $data
        ], 200);

    }
    public function show($id){
        $data = User::find($id);
        return view('dashboard.nasabah.view', compact('data'));
    }
    public function edit($id){
        $data = User::find($id);
        return view('dashboard.nasabah.edit', compact('data'));
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
            $user = User::find($id);
            if ($request->hasFile('photo')) {
                if ($user->photo && Storage::exists('public/' . $user->photo)) {
                    Storage::delete('public/' . $user->photo);
                }

                // Upload file baru
                $fileName = time() . '.' . $request->photo->extension();
                $request->photo->storeAs('foto-profile', $fileName, 'public');
                $user->photo = 'storage/foto-profile/' . $fileName;
            }

            $user->name = $request->name;
            $user->no_phone = $request->no_phone;
            $user->alamat = $request->alamat;

            // Update password jika diisi
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user,
            ], 200);
        } catch (Exception $e) {
            // Log::error('error',[$e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error update user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id){
        try {
            DB::beginTransaction();
        
            $nasabah = User::findOrFail($id);
            $nasabah->roles()->detach(); // Hapus relasi roles
            $nasabah->delete(); 
        
            DB::commit();
        
            return response()->json([
                'success' => true,
                'message' => 'Nasabah berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
        
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }

}
