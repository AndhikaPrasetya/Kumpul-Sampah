<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sampah extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'image',
        'harga',
        'category_id',
    ];

    public function categories ()
    {
        return $this->belongsTo(CategorySampah::class, 'category_id');
    }
}
