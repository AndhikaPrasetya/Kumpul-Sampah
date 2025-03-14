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
        $rewards = Rewards::where('bsu_id',$bsuId)->get();

        //list transaksi berddasarkan user_id
        

        return view('frontend.home', compact('user', 'articles', 'rewards'));
    }

    public function listRewards()
    {
        $rewards = Rewards::all();
        return view('frontend.rewards', compact('rewards'));
    }

    public function listBlog()
    {
        // Ambil berita terbaru sebagai hero berdasarkan created_at
        $heroNews = Article::latest()->first();

        // Ambil berita lainnya, kecuali hero
        $otherNews = Article::where('id', '!=', optional($heroNews)->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.blog.list', compact('heroNews', 'otherNews'));
    }

    public function detailBlog($slug)
    {
        $article = Article::with('user')->where('slug', $slug)->first();
        return view('frontend.blog.detail', compact('article'));
    }
}
