<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NasabahDetail extends Model
{
    protected $fillable = ['user_id', 'photo', 'alamat'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bsu()
{
    return $this->belongsTo(User::class, 'bsu_id');
}
}
