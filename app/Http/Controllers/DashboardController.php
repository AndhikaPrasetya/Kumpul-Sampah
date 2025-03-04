<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use App\Models\Transactions;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //hitung total sampah dari transactions 
    public function total()
    {
        $totalSampah = TransactionDetail::whereHas('transaction', function ($query) {
            $query->where('status', 'approved');
        })->sum('berat');
        
        $totalSaldo = Transactions::where('status', 'approved')->sum('total_amount');
        
        $transactions =Transactions::with(['users','details'])->where('status', 'pending')->get();
        return view('dashboard.index', get_defined_vars());
    }
   

}
