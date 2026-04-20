<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VolunteerController;

// Route Terbuka (Public)
Route::post('/login/donor', [AuthController::class, 'loginDonor']);
Route::get('/volunteers', [VolunteerController::class, 'index']);
Route::get('/volunteers/{id}', [VolunteerController::class, 'show']);

// Route Tertutup (Hanya bisa diakses Flutter jika mengirimkan Token Login)
Route::middleware('auth:sanctum')->group(function () {

    // Relawan update lokasi dirinya sendiri
    Route::post('/volunteers/{id}/location', [VolunteerController::class, 'updateLocation']);
});
    