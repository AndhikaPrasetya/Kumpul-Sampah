<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorySampah extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi'
    ];

    public function sampah(){
        return $this->hasMany(Sampah::class);
    }

}
