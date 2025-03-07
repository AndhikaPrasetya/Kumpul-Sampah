<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "Artikel";
        $breadcrumb = "artikel";
        if ($request->ajax()) {
            $data = Article::with('user');
            if ($search = $request->input('search.value')) {
                $data->where(function ($data) use ($search) {
                    $data->where('nama', 'like', "%{$search}%");
                });
            }

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    return $data->title;
                })
                ->addColumn('user_id', function ($data) {
                    return $data->user->name;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status === 'draft') {
                        return '<span class="badge badge-primary">' . $data->status . '</span>';
                    } elseif ($data->status === 'published') {
                        return '<span class="badge badge-danger">' . $data->status . '</span>';
                    } elseif ($data->status === 'archived') {
                        return '<span class="badge badge-warning">' . $data->status . '</span>';
                    } else {
                        return '<span class="badge badge-secondary">' . $data->status . '</span>';
                    }
                })

                ->addColumn('tanggal', function ($data) {
                    return Carbon::parse($data->tanggal)->format('d-m-Y');
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="text-center">';
                    //Check permission for adding/editing permissions
                    if (Gate::allows('update article')) {
                        $buttons .= '<a href="' . route('article.edit', $data->id) . '" class="btn btn-sm btn-primary mr-1">
                        <i class="fas fa-edit"></i>
                     </a>';
                    }
                    // Check permission for deleting permissions
                    if (Gate::allows('delete article')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-danger mr-1 delete-button" data-id="' . $data->id . '" data-section="article">' .
                            '<i class="fas fa-trash-alt"></i> 
                                     </button>';
                    }
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('dashboard.article.index', get_defined_vars());
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.article.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $slug = Str::slug($request->title, '-');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName =  uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('article', $fileName, 'public');
                $fileData = 'storage/article/' . $fileName;
            } else {
                throw new \Exception('Image file is required');
            }

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $fileName =  uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('article', $fileName, 'public');
                $fileDataThumbnail = 'storage/article/' . $fileName;
            } else {
                throw new \Exception('Thumbnail file is required');
            }

            Article::create([
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
                'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
                'image' => $fileData,
                'tanggal' => now(),
                'thumbnail' => $fileDataThumbnail,
                'user_id' => Auth::user()->id,
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Article created successfully'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Article::findOrFail($id);
        return view('dashboard.article.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            DB::beginTransaction();

            $article = Article::findOrFail($id);

            // Cek apakah title berubah
            if ($request->title && $request->title !== $article->title) {
                $slug = Str::slug($request->title);

                // Cek apakah slug sudah ada di database
                $count = Article::where('slug', $slug)->where('id', '!=', $article->id)->count();

                // Jika slug sudah ada, tambahkan angka unik
                if ($count > 0) {
                    $slug = $slug . '-' . uniqid();
                }
            } else {
                $slug = $article->slug; // Jika title tidak berubah, gunakan slug lama
            }

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($article->image && file_exists(public_path($article->image))) {
                    unlink(public_path($article->image));
                }

                $file = $request->file('image');
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('article', $fileName, 'public');
                $fileData = 'storage/article/' . $fileName;
            } else {
                $fileData = $article->image;
            }


            if ($request->hasFile('thumbnail')) {
                // Hapus gambar lama jika ada
                if ($article->thumbnail && file_exists(public_path($article->thumbnail))) {
                    unlink(public_path($article->thumbnail));
                }

                $file = $request->file('thumbnail');
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('article', $fileName, 'public');
                $fileDataThumbnail = 'storage/article/' . $fileName;
            } else {
                $fileDataThumbnail = $article->thumbnail;
            }

            $article->update([
                'title' => $request->title ?? $article->title,
                'content' => $request->content ?? $article->content,
                'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d'),
                'status' => $request->status,
                'image' => $fileData,
                'slug' => $slug,
                'thumbnail' => $fileDataThumbnail,
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Article updated successfully'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id){
        $data = Article::findOrFail($id);
        if(!$data){
            return response()->json([
                'status' => false,
                'message' => 'Article tidak ditemukan',
                ], 404);       
        }
    
        if($data->image){
            $image_path =$data->image;
            if(file_exists($image_path)){
                unlink($image_path);
                }
        }
        if($data->thumbnail){
            $thumbnail_path =$data->thumbnail;
            if(file_exists($thumbnail_path)){
                unlink($thumbnail_path);
                }
        }
        
        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil dihapus',
            ], 200);
    
    }
}
