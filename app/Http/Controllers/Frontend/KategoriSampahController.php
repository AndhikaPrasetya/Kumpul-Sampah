<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Sampah;
use Illuminate\Http\Request;
use App\Models\NasabahDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KategoriSampahController extends Controller
{
    public function listSampah()
    {
        $user = Auth::user();
        $nasabahDetail = NasabahDetail::where('user_id', $user->id)->first();
        $bsuId = $nasabahDetail ? $nasabahDetail->bsu_id : null;
    
        // Ambil semua sampah yang terkait dengan BSU
        $sampahs = Sampah::with('categories')
                    ->where('bsu_id', $bsuId)
                    ->get()
                    ->map(function ($item) {
                        $item->kategori_nama = $item->categories->nama ?? 'Tanpa Kategori';
                        return $item;
                    });
    
        return view('frontend.sampah.list', compact('sampahs'), [
            'route' => route('home')
        ]);
    }

    public function detailKategori($kategori){
        $user = Auth::user();
        $nasabahDetail = NasabahDetail::where('user_id', $user->id)->first();
        $bsuId = $nasabahDetail? $nasabahDetail->bsu_id : null;
        // Ambil semua sampah yang terkait dengan BSU dan kategori tertentu
        $sampahs = Sampah::with('categories')
                    ->where('bsu_id', $bsuId)
                    ->whereHas('categories', function ($query) use ($kategori) {
                        $query->where('nama', $kategori);
                    })
                    ->get()
                    ->map(function ($item) {
                        $item->kategori_nama = $item->categories->nama?? 'Tanpa Kategori';
                        return $item;
                    });
                  
        return view('frontend.sampah.detail_kategori_sampah', compact('sampahs'), [
            'route' => route('home')
        ]);

    }
    
}
