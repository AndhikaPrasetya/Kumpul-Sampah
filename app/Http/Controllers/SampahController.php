<?php

namespace App\Http\Controllers;

use App\Models\Sampah;
use Illuminate\Http\Request;
use App\Models\CategorySampah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SampahController extends Controller
{
    public function index(Request $request)
    {
        $title = "Data sampah";
        $breadcrumb = "sampah";
        if ($request->ajax()) {
            $data = Sampah::with('categories');
            if ($search = $request->input('search.value')) {
                $data->where(function ($data) use ($search) {
                    $data->where('nama', 'like', "%{$search}%");
                });
            }

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                })
                ->addColumn('category_id', function ($data) {
                    return $data->categories->nama;
                })
                ->addColumn('harga', function ($data) {
                    return 'Rp ' . number_format($data->harga, 0, ',', '.');
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="text-center">';
                    //Check permission for adding/editing permissions
                    if (Gate::allows('update sampah')) {
                        $buttons .= '<a href="' . route('sampah.edit', $data->id) . '" class="btn btn-sm btn-primary mr-1">
                        <i class="fas fa-edit"></i>
                     </a>';
                    }
                    // Check permission for deleting permissions
                    if (Gate::allows('delete sampah')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-danger mr-1 delete-button" data-id="' . $data->id . '" data-section="sampah">' .
                            '<i class="fas fa-trash-alt"></i> 
                                     </button>';
                    }
                    if (Gate::allows('read sampah')) {
                        $buttons .= '<a href="' . route('sampah.show', $data->id) . '" class="btn btn-sm btn-info btn-show-user">
                    <i class="fas fa-eye"></i>
                 </a>';
                    }
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.sampah.index', get_defined_vars());
    }

    public function create()
    {
        $categories = CategorySampah::all();
        return view('dashboard.sampah.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'        => 'required|string|max:255',
            'category_id' => 'required|integer',
            'harga'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'deskripsi'   => 'required|string',
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
                $image = $request->file('image');
                $image_name = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('sampah'), $image_name);
            } else {
                throw new \Exception('Image file is required');
            }

            $data = Sampah::create([
                'nama' => $request->nama,
                'category_id' => $request->category_id,
                'harga' => $request->harga,
                'image' => $image_name,
                'deskripsi' => $request->deskripsi,
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

    public function edit($id)
    {
        $data = Sampah::find($id);
        $categories = CategorySampah::all();
        return view('dashboard.sampah.edit', get_defined_vars());
    }
    public function show($id)
    {
        $data = Sampah::find($id);
        $categories = CategorySampah::all();
        return view('dashboard.sampah.detail', get_defined_vars());
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'nama'        => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|integer',
            'harga'       => 'sometimes|required',
            'image'       => 'sometimes|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'deskripsi'   => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
    
            $data = Sampah::find($id);
            if (!$data) {
                return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
            }
    
            $updateData = [];
    
            if ($request->filled('nama')) {
                $updateData['nama'] = $request->nama;
            }
            if ($request->filled('category_id')) {
                $updateData['category_id'] = $request->category_id;
            }
            if ($request->filled('harga')) {
                $updateData['harga'] = (int) str_replace('.', '', $request->harga);
            }
            if ($request->filled('deskripsi')) {
                $updateData['deskripsi'] = $request->deskripsi;
            }

         // Handle image upload
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($data->image && file_exists(public_path($data->image))) {
                unlink(public_path($data->image));
            }

            // Simpan gambar baru
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('sampah', $fileName, 'public');
            $fileData = 'storage/sampah/' . $fileName;

            // Tambahkan path gambar ke array updateData
            $updateData['image'] = $fileData;
        }

    
            $data->update($updateData);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data Berhasil diperbarui',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }


public function destroy($id){
    $data = Sampah::findOrFail($id);
    if(!$data){
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan',
            ], 404);       
    }

    if($data->image){
        $image_path = 'sampah/'. $data->image;
        if(file_exists($image_path)){
            unlink($image_path);
            }
    }
    
    $data->delete();
    return response()->json([
        'status' => true,
        'message' => 'Data Berhasil dihapus',
        ], 200);

}
}
