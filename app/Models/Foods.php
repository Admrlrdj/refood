<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // PASTIKAN PAKAI YANG INI

class Foods extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'foods';

    protected $fillable = [
        'nama_makanan',
        'porsi',
        'lokasi_restoran',
        'deskripsi',
        'status', // 'tersedia' atau 'diambil'
    ];
}
