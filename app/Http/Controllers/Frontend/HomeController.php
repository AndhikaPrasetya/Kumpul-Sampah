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

        $saldo = Saldo::where('user_id', $user->id)->first();

        //list articles for home
        $articles = Article::where('status', 'published')->get();

        //list rewards for home
        $rewards = Rewards::where('bsu_id',$bsuId)->get();

        //list transaksi berddasarkan user_id
        // Using a collection instead for section transactions
        $withdrawals = DB::table('withdraws')->select('id', 'user_id', 'status', 'amount', 'created_at')
            ->where('user_id', $user->id)
            ->get();
        $pointExchanges = DB::table('penukaran_points')->select('id', 'user_id', 'reward_id', 'status', 'total_points', 'created_at')
            ->where('user_id', $user->id)
            ->get();
        $wasteDeposits = DB::table('transactions')->select('id', 'transaction_code', 'user_id', 'status', 'total_amount', 'total_points', 'created_at')
            ->where('user_id', $user->id)
            ->get();

        // Tambahkan properti type berdasarkan variabel yang digunakan
        $withdrawalsWithType = $withdrawals->map(function ($item) {
            $item->type = 'tarik_tunai';
            return $item;
        });

        $pointExchangesWithType = $pointExchanges->map(function ($item) {
            $item->type = 'tukar_points';
            return $item;
        });

        $wasteDepositsWithType = $wasteDeposits->map(function ($item) {
            $item->type = 'setor_sampah';
            return $item;
        });

        // Gabungkan dan urutkan
        $transactions = $withdrawalsWithType->concat($pointExchangesWithType)->concat($wasteDepositsWithType)
            ->sortByDesc('created_at')->take(3);

        return view('frontend.home', compact('user', 'saldo', 'articles', 'rewards', 'transactions'));
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
