<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Saldo;
use App\Models\NasabahDetail;

class SaldoInfo extends Component
{
    public $saldo;
    public $points;

    public function mount()
    {
        $this->updateSaldo();
    }

    public function updateSaldo()
    {
        $user = Auth::user();

        $saldoData = Saldo::where('user_id', $user->id)->first();
  
        $this->saldo = $saldoData ? number_format($saldoData->balance, 0, ',', '.') : '0';
        $this->points = $saldoData ? number_format($saldoData->points, 0, ',', '.') . ' Poin' : '0 Poin';
    }

    public function render()
    {
        // Stop polling if not authenticated
    if (!auth()->check()) {
        return null;
    }
        return view('livewire.saldo-info');
    }

    
}