<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BsuDetail;
use App\Models\Transactions;
use App\Models\User;
use Illuminate\Http\Request;

class PeringkatController extends Controller
{
    public function index()
    {
        $bsu = BsuDetail::with('user')->get();

        $transactions = collect();
        foreach ($bsu as $b) {
            $transactions = $transactions->merge(
                Transactions::where('status', 'approved')
                    ->where('bsu_id', $b->id)
                    ->get()
            );
        }

        dd($transactions);
        return view('frontend.leaderboard.index');
    }
}
