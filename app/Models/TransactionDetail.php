<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = ['transaction_id', 'sampah_id', 'berat', 'subtotal'];

    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }
}
