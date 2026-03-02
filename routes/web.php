<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\VolunteerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.pages.dashboard.ecommerce');
    })->name('admin.dashboard');

    // -- ROUTE VERIFIKASI RELAWAN --
    Route::get('/admin/volunteers', [VolunteerController::class, 'index'])->name('admin.volunteers');
    Route::post('/admin/volunteers', [VolunteerController::class, 'store'])->name('admin.volunteers.store');
    Route::put('/admin/volunteers/{id}', [VolunteerController::class, 'update'])->name('admin.volunteers.update');
    Route::delete('/admin/volunteers/{id}', [VolunteerController::class, 'destroy'])->name('admin.volunteers.destroy');
    Route::post('/admin/volunteers/{id}/verify', [VolunteerController::class, 'verify'])->name('admin.volunteers.verify');
    Route::post('/admin/volunteers/{id}/reject', [VolunteerController::class, 'reject'])->name('admin.volunteers.reject');
});
