<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $fillable = ['user_id', 'tanggal', 'total_amount','total_points','status','transaction_code'];

    public static function boot()
{
    parent::boot();
    static::creating(function ($transaction) {
        $date = now()->format('Ymd');
        $lastTransaction = Transactions::whereDate('tanggal', now()->toDateString())->latest()->first();
        $nextNumber = $lastTransaction ? ((int) substr($lastTransaction->transaction_code, -3) + 1) : 1;
        $transaction->transaction_code = 'BS-' . $date . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    });
}


    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
