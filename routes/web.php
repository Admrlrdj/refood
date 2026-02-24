<?php

use Illuminate\Support\Facades\Route;
use App\Models\Foods;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/add-dummy', function () {
    Foods::create([
        'nama_makanan' => 'Roti Bakar Cokelat',
        'porsi' => 5,
        'lokasi_restoran' => 'Cafe Mentari',
        'deskripsi' => 'Kelebihan stok roti hari ini, kondisi sangat baik.',
        'status' => 'tersedia'
    ]);

    return "Data Roti Bakar berhasil ditambah ke MongoDB Atlas!";
});