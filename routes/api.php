<?php

use App\Http\Controllers\BengkelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/get_data', [BengkelController::class, 'index']);
