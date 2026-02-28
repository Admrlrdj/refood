<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route Auth Relawan
Route::post('/register/volunteer', [AuthController::class, 'registerVolunteer']);
Route::post('/login/volunteer', [AuthController::class, 'loginVolunteer']);
