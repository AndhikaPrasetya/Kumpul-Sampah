<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Data Permission";
        $breadcrumb = "Permission";
        if($request->ajax()){
            $data = Permission::query();
            if($search= $request->input('search.value')){
                $data->where(function($data) use ($search){
                    $data->where('name','like',"%{$search}%");
                });
            }
        
        return DataTables::eloquent($data)
        ->addIndexColumn()
        ->addColumn('Name', function($data){
            return $data->name;
        })
        ->addColumn('Guard Name', function($data){
            return $data->guard_name;
        })
        ->addColumn('action', function ($data) {
            $buttons = '<div class="text-center">';
                    //Check permission for adding/editing permissions
                    if (Gate::allows('update permission')) {
                        $buttons .= '<a href="' . route('permission.edit', $data->id) . '" class="btn btn-sm btn-primary mr-1">
                        <i class="fas fa-edit"></i> Edit
                     </a>';
                    }
                    // Check permission for deleting permissions
                    if (Gate::allows('delete permission')) {
                       $buttons .= '<button type="button" class="btn btn-sm btn-danger mr-1 delete-button" data-id="' . $data->id . '" data-section="permission">'.
                                    '<i class="fas fa-trash-alt"></i> Delete
                                     </button>';
                    }
                    $buttons .= '</div>';
                    return $buttons;
        })
        ->rawColumns([ 'action'])
        ->make(true);
        }
        return view('dashboard.permission.index',get_defined_vars());
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Create Permission";
        $breadcrumb = "Create Permission";
        return view('dashboard.permission.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),  [
            'name' => 'required|unique:permissions,name',
        ]);
        if ($validator->fails()) {
            return response()->json([
            'status'=>true,
            'message'=>'validation failed',
            'errors'=>$validator->errors()], 422);
        }

        $data = Permission::create([
            'name' => $request->name,
            Auth::guard('web')
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Permission created successfully',
            'permission_id' => $data->id
        ], 200);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('permission::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $title = "Edit Permission";
        $breadcrumb = "Edit Permission";
        $data = Permission::find($id);
        return view('dashboard.permission.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),  [
            'name' => 'required|unique:permissions,name',
        ]);
        if ($validator->fails()) {
            return response()->json([
            'status'=>true,
            'message'=>'validation failed',
            'errors'=>$validator->errors()], 422);
        }


        $data = Permission::find($id);
        $data->name = $request->name;
        $data->save();
        
        return response()->json([
            'status' => true,
            'message' => 'Permission updated successfully',
            'data' => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Permission::find($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully',
            ], 200);
    }
}
