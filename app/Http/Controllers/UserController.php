<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Data Users";
        $breadcrumb = "Users";
        if ($request->ajax()) {
            $data = User::query();
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
                ->addColumn('role', function ($data) {
                    $roles = $data->getRoleNames();
                    $rolesList = '';

                    foreach ($roles as $role) {
                        $rolesList .= '<span class="badge badge-primary">' . $role . '</span> ';
                    }

                    return $rolesList;
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="text-center">';

                    if (Gate::allows('update user')) {
                        $buttons .= '<a href="' . route('users.edit', $data->id) . '" class="btn btn-sm btn-primary mr-1">
                                        <i class="fas fa-edit"></i> Edit
                                     </a>';
                    }
                    
                    if (Gate::allows('delete user')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-danger mr-1 delete-button" data-id="' . $data->id . '" data-section="users">'.
                                    '<i class="fas fa-trash-alt"></i> Delete
                                     </button>';
                    }
                    
                    if (Gate::allows('read user')) {
                        $buttons .= '<a href="' . route('users.show', $data->id) . '" class="btn btn-sm btn-info btn-show-user">
                    <i class="fas fa-eye"></i> View
                 </a>';
                    }
                    
                    $buttons .= '</div>';
                    

                    return $buttons;
                })
                ->rawColumns(['action', 'role'])
                ->make(true);
        }
        return view('dashboard.user.index', get_defined_vars());
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Create Data Users";
        $breadcrumb = "Create Users";
        $roles = Role::pluck('name', 'name')->all();
        return view('dashboard.user.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'no_phone' => 'nullable|string|max:255',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'password' => 'required|string|min:8',
            'alamat' => 'nullable|string',
        ]);

        //check if validation fails
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

        if (!empty($request->roles)) {
            $data->syncRoles($request->roles);
        }

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'userId' => $data->id,

        ], 200);
    }


    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $data->roles->pluck('name', 'name')->all();
        return view('dashboard.user.view', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $data->roles->pluck('name', 'name')->all();
        return view('dashboard.user.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
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

            // Update role
            $user->syncRoles($request->roles);
            $user->save();

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ], 200);
    }
}
