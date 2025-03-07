<?php

namespace App\Http\Controllers;

use App\Models\Rewards;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RewardsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Data rewards";
        $breadcrumb = "rewards";
        if ($request->ajax()) {
            $data = Rewards::query();
            if ($search = $request->input('search.value')) {
                $data->where(function ($data) use ($search) {
                    $data->where('name', 'like', "%{$search}%");
                });
            }

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('points', function ($data) {
                    return  number_format($data->points, 0, ',', '.');
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
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.rewards.index', get_defined_vars());
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.rewards.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'points' => 'required',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('rewards', $fileName, 'public');
                $fileData = 'storage/rewards/' . $fileName;
            } else {
                throw new \Exception('Image file is required');
            }

            $data = Rewards::create([
                'name' => $request->name,
                'points' => str_replace('.', '', $request->points ?? 0),
                'image' => $fileData,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dibuat',
                'data' => $data
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rewards = Rewards::findOrFail($id);
        return view('dashboard.rewards.detail', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rewards = Rewards::findOrFail($id);
        return view('dashboard.rewards.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'sometimes|required|string|max:255',
            'points' => 'sometimes|required|integer',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'success' => false,
               'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        try{
            DB::beginTransaction();
            $rewards = Rewards::findOrFail($id);
            if (!$rewards) {
                return response()->json(['status' => false, 'message' => 'Rewards tidak ditemukan'], 404);
            }
    
            $updateData = [];
        
            if ($request->filled('name')) {
                $updateData['name'] = $request->name;
            }
            if ($request->filled('points')) {
                $updateData['points'] = str_replace('.', '', $request->points ?? 0);
            }
            
             // Handle image upload
             if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($rewards->image && file_exists(public_path($rewards->image))) {
                    unlink(public_path($rewards->image));
                }
    
                // Simpan gambar baru
                $file = $request->file('image');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('rewards', $fileName, 'public');
                $fileData = 'storage/rewards/' . $fileName;
    
                // Tambahkan path gambar ke array updateData
                $updateData['image'] = $fileData;
            }
            $rewards->update($updateData);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Rewards Berhasil diperbarui',
            ], 200);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => false,
               'message' => 'Terjadi kesalahan saat mengupdate rewards.',
                'errors' => $e->getMessage()
            ], 500);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rewards = Rewards::findOrFail($id);
        if (!$rewards) {
            return response()->json(['status' => false,'message' => 'Rewards not found'], 404);
        }
        if($rewards->image){
            $image_path = $rewards->image;
            if(file_exists($image_path)){
                    unlink($image_path);
                }
        }
        $rewards->delete();
        return response()->json(['status' => true,'message' => 'Rewards deleted successfully'], 200);
    }
}
