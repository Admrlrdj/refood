<?php

use Illuminate\Support\Facades\Route;
use App\Models\Foods;

Route::get('/', function () {
    return view('welcome');
});