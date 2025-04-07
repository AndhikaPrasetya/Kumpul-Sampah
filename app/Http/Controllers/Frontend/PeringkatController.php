<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BsuDetail;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use App\Models\User;
use Illuminate\Http\Request;

class PeringkatController extends Controller
{
    public function index()
    {
        $bsu = BsuDetail::with('user')->get();

        // $transactions = collect();
        // foreach ($bsu as $b) {
        //     $transactions = $transactions->merge(
        //         Transactions::where('status', 'approved')
        //             ->where('bsu_id', $b->id)
        //             ->get()
        //     );
        // }
        $totalSaldo = TransactionDetail::whereHas('transaction', function ($q){
            $q->where('status','approved');
        })->sum('subtotal');
        $totalBerat = TransactionDetail::whereHas('transaction', function ($q){
            $q->where('status','approved');
        })->sum('berat');
        $totalBsu = BsuDetail::count('id');
       
        $transactionsPerBSU = $bsu->map(function ($bsu) {
            return (object) [
                'bsu_id' => $bsu->id,
                'bsu_name' => $bsu->user->name ?? 'Unknown',
                'total_berat' => TransactionDetail::whereHas('transaction', function ($query) use ($bsu) {
                    $query->where('status', 'approved')
                        ->where('bsu_id', $bsu->id);
                })->sum('berat')
            ];
        })->sortByDesc('total_berat')->values();
        
        $topThree = $transactionsPerBSU->take(3); // peringkat 1-3
        $others = $transactionsPerBSU->slice(3)->values(); // sisanya dari peringkat 4
  
        return view('frontend.leaderboard.index',get_defined_vars(),[
            'route'=>route('home')
        ]);
    }
}
