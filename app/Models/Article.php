<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'content', 'image','thumbnail', 'user_id','status','tanggal','slug'];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
