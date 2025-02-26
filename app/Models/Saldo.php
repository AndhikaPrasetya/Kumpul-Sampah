<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    protected $fillable = ['user_id', 'saldo_masuk', 'saldo_keluar', 'saldo_akhir'];

    public function nasabah(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
