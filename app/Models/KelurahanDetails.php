<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelurahanDetails extends Model
{
        protected $fillable = ['user_id', 'name','kota', 'kecamatan','provinsi', 'alamat', 'kode_pos'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
