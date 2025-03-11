<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenukaranPoints extends Model
{
    protected $fillable = ['user_id', 'reward_id', 'bsu_id','total_points', 'status'];
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reward(){
        return $this->belongsTo(Rewards::class, 'reward_id');
    }
}
