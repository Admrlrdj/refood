<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\VolunteerController;
use App\Http\Controllers\Admin\DonorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReceiverController;
use App\Http\Controllers\Admin\FoodController;

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

    // -- ROUTE RELAWAN --
    Route::get('/admin/volunteers', [VolunteerController::class, 'index'])->name('admin.volunteers');
    Route::post('/admin/volunteers', [VolunteerController::class, 'store'])->name('admin.volunteers.store');
    Route::put('/admin/volunteers/{id}', [VolunteerController::class, 'update'])->name('admin.volunteers.update');
    Route::delete('/admin/volunteers/{id}', [VolunteerController::class, 'destroy'])->name('admin.volunteers.destroy');
    Route::post('/admin/volunteers/{id}/verify', [VolunteerController::class, 'verify'])->name('admin.volunteers.verify');
    Route::post('/admin/volunteers/{id}/reject', [VolunteerController::class, 'reject'])->name('admin.volunteers.reject');

    // -- ROUTE DATA ADMIN (USERS) --
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // -- ROUTE DATA DONATUR --
    Route::get('/admin/donors', [DonorController::class, 'index'])->name('admin.donors');
    Route::post('/admin/donors', [DonorController::class, 'store'])->name('admin.donors.store');
    Route::put('/admin/donors/{id}', [DonorController::class, 'update'])->name('admin.donors.update');
    Route::delete('/admin/donors/{id}', [DonorController::class, 'destroy'])->name('admin.donors.destroy');
    Route::post('/admin/donors/{id}/verify', [DonorController::class, 'verify'])->name('admin.donors.verify');
    Route::post('/admin/donors/{id}/reject', [DonorController::class, 'reject'])->name('admin.donors.reject');

    // -- ROUTE DATA RECEIVERS (PENERIMA/YAYASAN) --
    Route::get('/admin/receivers', [ReceiverController::class, 'index'])->name('admin.receivers');
    Route::post('/admin/receivers', [ReceiverController::class, 'store'])->name('admin.receivers.store');
    Route::put('/admin/receivers/{id}', [ReceiverController::class, 'update'])->name('admin.receivers.update');
    Route::delete('/admin/receivers/{id}', [ReceiverController::class, 'destroy'])->name('admin.receivers.destroy');

    Route::post('/admin/receivers/{id}/verify', [ReceiverController::class, 'verify'])->name('admin.receivers.verify');
    Route::post('/admin/receivers/{id}/reject', [ReceiverController::class, 'reject'])->name('admin.receivers.reject');

    // -- ROUTE DATA MAKANAN (FOODS) --
    Route::get('/admin/foods', [FoodController::class, 'index'])->name('admin.foods');
    Route::post('/admin/foods', [FoodController::class, 'store'])->name('admin.foods.store');
    Route::put('/admin/foods/{id}', [FoodController::class, 'update'])->name('admin.foods.update');
    Route::delete('/admin/foods/{id}', [FoodController::class, 'destroy'])->name('admin.foods.destroy');
});
