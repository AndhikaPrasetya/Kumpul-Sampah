<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BsuDetail extends Model
{
    protected $fillable = ['user_id', 'rt', 'rw','alamat','status','kelurahan_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function kelurahan()
    {
        return $this->belongsTo(KelurahanDetails::class);
    }
}
