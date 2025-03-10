<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //hitung total sampah dari transactions 
    public function total()
    {
        $bsu_id = optional(Auth::user())->id; 

        $totalSampah = TransactionDetail::whereHas('transaction', function ($query) use ($bsu_id) {
            $query->where('status', 'approved')->where('bsu_id', $bsu_id);
        })->sum('berat');
        
        $totalSaldo = Transactions::where('bsu_id', $bsu_id)
            ->where('status', 'approved')
            ->sum('total_amount');
        
        $transactions = Transactions::with(['users', 'details'])
            ->where('status', 'pending')
            ->get();
        
        return view('dashboard.index', get_defined_vars());
        
    }
   

}
