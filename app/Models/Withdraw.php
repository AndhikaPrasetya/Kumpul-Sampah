<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $fillable = ['user_id','bsu_id', 'amount', 'tanggal', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
