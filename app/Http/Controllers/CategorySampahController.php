<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategorySampah;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class CategorySampahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Data Kategori sampah";
        $breadcrumb = "Kategori sampah";
        if($request->ajax()){
            $data = CategorySampah::query();
            if($search= $request->input('search.value')){
                $data->where(function($data) use ($search){
                    $data->where('nama','like',"%{$search}%");
                });
            }
        
        return DataTables::eloquent($data)
        ->addIndexColumn()
        ->addColumn('nama', function($data){
            return $data->nama;
        })
        ->addColumn('deskripsi', function($data){
            return $data->deskripsi;
        })
        ->addColumn('action', function ($data) {
            $buttons = '<div class="text-center">';
                    //Check permission for adding/editing permissions
                    if (Gate::allows('update kategori')) {
                        $buttons .= '<a href="' . route('kategori-sampah.edit', $data->id) . '" class="btn btn-sm btn-primary mr-1">
                        <i class="fas fa-edit"></i>
                     </a>';
                    }
                    // Check permission for deleting permissions
                    if (Gate::allows('delete kategori')) {
                       $buttons .= '<button type="button" class="btn btn-sm btn-danger mr-1 delete-button" data-id="' . $data->id . '" data-section="kategori-sampah">'.
                                    '<i class="fas fa-trash-alt"></i> 
                                     </button>';
                    }
                    if (Gate::allows('read kategori')) {
                        $buttons .= '<a href="' . route('kategori-sampah.show', $data->id) . '" class="btn btn-sm btn-info btn-show-user">
                    <i class="fas fa-eye"></i>
                 </a>';
                    }
                    $buttons .= '</div>';
                    return $buttons;
        })
        ->rawColumns([ 'action'])
        ->make(true);
        }
        return view('dashboard.kategori-sampah.index',get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.kategori-sampah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),  [
            'nama' => 'required',
            'deskripsi' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
            'status'=>true,
            'message'=>'validation failed',
            'errors'=>$validator->errors()], 422);
        }

        $data = CategorySampah::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dibuat',
            'kategori' => $data
        ], 200);
    }
    public function show(string $id)
    {
        $data = CategorySampah::findOrFail($id);
        if(!$data){
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan',
                ], 404);
        }
        return view('dashboard.kategori-sampah.detail',get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = CategorySampah::findOrFail($id);
        if(!$data){
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan',
                ], 404);
        }
        return view('dashboard.kategori-sampah.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),  [
            'nama' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
            'success'=>true,
            'message'=>'validation failed',
            'errors'=>$validator->errors()], 422);
        }
        $data = CategorySampah::findOrFail($id);
        $data->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);
        
        return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diubah',
                'kategori' => $data
                ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = CategorySampah::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus',
            ], 200);
    }
}
