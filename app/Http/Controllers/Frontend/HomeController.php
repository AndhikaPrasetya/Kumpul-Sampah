<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Rewards;
use App\Models\Saldo;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        $user =Auth::user();
        $saldo = Saldo::where('user_id', $user->id)->first();
        $articles = Article::where('status','published')->get();
        $rewards = Rewards::all();
        return view('frontend.home', compact('user','saldo','articles','rewards'));
    }
}
