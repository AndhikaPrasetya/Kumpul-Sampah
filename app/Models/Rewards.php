<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rewards extends Model
{
    protected $fillable=[
        'name',
        'points',
        'image',
        'bsu_id',
        'stok',
        'deskripsi',
        'tanggal_expired'
    ];
    public function penukaranPoints(){
        return $this->hasMany(PenukaranPoints::class, 'reward_id');
    }
}
