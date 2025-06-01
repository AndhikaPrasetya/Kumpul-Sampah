<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $fillable = ['user_id','bsu_id', 'amount', 'tanggal', 'status','metode_penarikan', 'transaction_code'];

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
            $transaction->transaction_code = "BS-WD-{$date}-{$random}{$sequence}";
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
