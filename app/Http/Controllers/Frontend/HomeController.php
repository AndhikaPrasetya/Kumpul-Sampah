<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Saldo;
use App\Models\Article;
use App\Models\Rewards;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\NasabahDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CategorySampah;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $nasabahDetail = NasabahDetail::where('user_id', $user->id)->first();

        // Jika nasabahDetail tidak ditemukan, set bsu_id ke null
        $bsuId = $nasabahDetail ? $nasabahDetail->bsu_id : null;

        //list articles for home
        $articles = Article::where('status', 'published')->get();

        //list rewards for home
        $rewards = Rewards::where('bsu_id', $bsuId)->get();


        $currentPoints = Saldo::where('user_id', $user->id)->value('points') ?? 0;

        $categorySampah = CategorySampah::all();


        return view('frontend.home', get_defined_vars());
    }

    public function listRewards()
    {
        $user = Auth::user();
        $bsuId = $this->getBsuId($user);
        
        // Ambil rewards dengan stok > 0 dan urutkan berdasarkan poin/created_at sesuai kebutuhan
        $rewards = Rewards::where('bsu_id', $bsuId)
                         ->orderBy('points', 'asc') // Urutkan dari poin terkecil
                         ->get();
        
        $currentPoints = Saldo::where('user_id', $user->id)->value('points') ?? 0;
    
        return view('frontend.rewards.list', [
            'rewards' => $rewards,
            'currentPoints' => $currentPoints, 
            'route' => route('home')
        ]);
    }

    public function listBlog()
    {
        // Ambil 3 berita terbaru sebagai hero berdasarkan created_at
        $heroNews = Article::latest()->where('status', 'published')->take(3)->get();

        $heroNewsIds = $heroNews->pluck('id')->toArray();

        $otherNews = Article::whereNotIn('id', $heroNewsIds)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.blog.list', compact('heroNews', 'otherNews'),[
            'route'=>route('home')
        ]);
    }
    public function detailBlog($slug)
    {
        $article = Article::with('user')->where('slug', $slug)->first();
        return view('frontend.blog.detail', compact('article'),[
            'route'=>route('listBlog')
        ]);
    }

    private function getBsuId($user)
    {
        $nasabahDetail = NasabahDetail::where('user_id', $user->id)->first();
        $bsuid = $nasabahDetail ? $nasabahDetail->bsu_id : null;
        return $bsuid;
    }
}
