<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BsuDetail extends Model
{
    protected $fillable = ['user_id', 'rt', 'rw','kelurahan','alamat'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
