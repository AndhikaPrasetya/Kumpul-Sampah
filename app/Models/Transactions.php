<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $fillable = ['user_id', 'tanggal', 'total_amount'];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
