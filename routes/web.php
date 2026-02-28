<?php

use Illuminate\Support\Facades\Route;

// Biarkan rute '/' yang asli bawaan Laravel
Route::get('/', function () {
    return view('welcome');
});

// Tambahkan rute khusus dashboard admin TailAdmin
Route::get('/admin/dashboard', function () {
    // Sesuaikan path ini dengan tempat kamu mem-paste file dashboard.blade.php
    // Misalnya file utamanya bernama 'index.blade.php' di folder admin:
    return view('admin.index'); // Atau 'admin.dashboard', cek nama filenya
});
