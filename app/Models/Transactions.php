<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $fillable = ['user_id','bsu_id', 'tanggal', 'total_amount','total_points','status','transaction_code'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($transaction) {
            $uuid = (string) Str::uuid();
            $date = now()->format('Ymd');
    
            $transaction->transaction_code = 'BS-' . $date . '-' . $uuid;
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
