<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeluargaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// Keluarga
Route::prefix('keluarga')->group(function () {
    Route::get('/find', [KeluargaController::class, 'findNIK']);
});
