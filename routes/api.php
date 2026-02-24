<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Foods;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/foods', function () {
    return Foods::all();
});