<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $fillable = ['user_id','bsu_id', 'tanggal', 'total_amount','total_points','status','transaction_code','notes'];

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($transaction) {
            // Get current date in shorter format YYMMDD
            $date = now()->format('ymd');
            
            // Generate a very short random string (3 characters)
            $random = strtoupper(Str::random(3));
            
            // Add a sequential counter based on current time to ensure uniqueness
            $sequence = base_convert(now()->format('His'), 10, 36);
            
            // Combine to create unique code
            $transaction->transaction_code = "BS-SS-{$date}-{$random}{$sequence}";
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
