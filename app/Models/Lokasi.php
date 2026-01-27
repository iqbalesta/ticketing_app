<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $fillable = [
        'nama',
        'alamat',
        'kota',
        'provinsi',
        'deskripsi',
        'kapasitas',
    ];

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }
}
