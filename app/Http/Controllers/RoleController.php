<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{


    public function index(Request $request)
    {
        $title = "Data Role";
        $breadcrumb = "Role";
        if ($request->ajax()) {
            $data = Role::query();
            if ($search = $request->input('search.value')) {
                $data->where(function ($data) use ($search) {
                    $data->where('name', 'like', "%{$search}%");
                });
            }

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('Name', function ($data) {
                    return $data->name;
                })
                ->addColumn('Guard Name', function ($data) {
                    return $data->guard_name;
                })
                ->addColumn('permission', function ($data) {
                    $permissions = $data->getPermissionNames();
                    $permissionsList = '';

                    foreach ($permissions as $permission) {
                        $permissionsList .= '<span class="badge badge-primary">' . $permission . '</span> ';
                    }

                    return $permissionsList;
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="text-center">';
                    //Check permission for adding/editing permissions
                    if (Gate::allows('update role')) {
                        $buttons .= '<a href="' . route('roles.edit', $data->id) . '" class="btn btn-sm btn-primary mr-1">
                        <i class="fas fa-edit"></i> Edit
                     </a>';
                    }
                    // Check permission for deleting roles
                    if (Gate::allows('delete role')) {
                       $buttons .= '<button type="button" class="btn btn-sm btn-danger mr-1 delete-button" data-id="' . $data->id . '" data-section="roles">'.
                                    '<i class="fas fa-trash-alt"></i> Delete
                                     </button>';
                    }
                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['action','permission'])
                ->make(true);
        }
        return view('dashboard.roles.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Create Role";
        $breadcrumb = "Create Role";
        $permissionGroups = [
            'Role' => Permission::whereIn('name', ['create role', 'read role', 'update role', 'delete role'])->get(),
            'Permission' => Permission::whereIn('name', ['create permission', 'read permission', 'update permission', 'delete permission'])->get(),
            'User' => Permission::whereIn('name', ['create user', 'read user', 'update user', 'delete user'])->get(),
            'Website Settings' => Permission::whereIn('name', ['create website setting','read website setting','update website setting','delete website setting'])->get(),
            'Kategori Sampah' => Permission::whereIn('name', ['create kategori', 'read kategori', 'update kategori', 'delete kategori'])->get(),
            'Sampah' => Permission::whereIn('name', ['create sampah', 'read sampah', 'update sampah', 'delete sampah'])->get(),
            'Saldo' => Permission::whereIn('name', ['create saldo', 'read saldo', 'update saldo', 'delete saldo'])->get(),
            'Transaction' => Permission::whereIn('name', ['create transaction', 'read transaction', 'update transaction', 'delete transaction'])->get(),
            'Penarikan' => Permission::whereIn('name', ['create withdraw', 'read withdraw', 'update withdraw', 'delete withdraw'])->get(),
        ];
        $permissions = Permission::get();
        return view('dashboard.roles.create',get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),  [
            'name' => 'required|unique:roles',
            'permission' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()->first('name')
            ], 422);
        }
        $role = new Role();
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permission);
       
        return response()->json([
            'status' => true,
            'message' => 'Role created successfully',
            'role_id' => $role->id
        ], 200);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('roles::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $title = "Edit Data Role";
        $breadcrumb = "Edit Role";
        $permissionGroups = [
            'Role' => Permission::whereIn('name', ['create role', 'read role', 'update role', 'delete role'])->get(),
            'Permission' => Permission::whereIn('name', ['create permission', 'read permission', 'update permission', 'delete permission'])->get(),
            'User' => Permission::whereIn('name', ['create user', 'read user', 'update user', 'delete user'])->get(),
            'Website Settings' => Permission::whereIn('name', ['create website setting','read website setting','update website setting','delete website setting'])->get(),
            'Kategori Sampah' => Permission::whereIn('name', ['create kategori', 'read kategori', 'update kategori', 'delete kategori'])->get(),
            'Sampah' => Permission::whereIn('name', ['create sampah', 'read sampah', 'update sampah', 'delete sampah'])->get(),
            'Saldo' => Permission::whereIn('name', ['create saldo', 'read saldo', 'update saldo', 'delete saldo'])->get(),
            'Transaction' => Permission::whereIn('name', ['create transaction', 'read transaction', 'update transaction', 'delete transaction'])->get(),
            'Penarikan' => Permission::whereIn('name', ['create withdraw', 'read withdraw', 'update withdraw', 'delete withdraw'])->get(),
        ];
        $permissions = Permission::get();
        $rolePermissions = DB::table('role_has_permissions')
        ->where('role_has_permissions.role_id', $role->id)
        ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')->all();
        return view('dashboard.roles.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),  [
            'name' => ['nullable',Rule::unique('roles')->ignore($id),],
            'permission' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $data = Role::find($id);
        $data->name = $request->name;
        $data->syncPermissions($request->permission);
        $data->save();
        return response()->json([
            'status' => true,
            'message' => 'Role updated successfully',
            'data' => $data
        ], 200);
    }


    public function givePermissionToRole(Request $request, $roleId)
    {
        $validator = Validator::make($request->all(),  [
            'permission' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()->first('permission')
            ], 422);
        }
        $role = Role::find($roleId);
        $role->syncPermissions($request->permission);

        return response()->json([
            'status' => true,
            'message' => 'Permission added successfully',
            'data' => $role
        ], 200);
    }

    public function getDataRole(Request $request)
    {
        $search = $request->input('search');

        $role = Role::get();

        if ($search) {
            $role->where('name', 'like', "%{$search}%");
        }
        return response()->json($role);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $data = Role::find($id);
        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'Role deleted successfully',
        ], 200);
    }
}
