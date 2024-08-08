<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeluargaController;
use App\Http\Controllers\KemandirianController;
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
    Route::post('/register', [KeluargaController::class, 'register']);
});

// Kemandirian
Route::middleware('isApproved')->prefix('kemandirian')->group(function(){
    Route::get('/available/{keluarga_id}', [KemandirianController::class, 'availableToAnswerQuestion']);
    Route::get('/questions/{keluarga_id}', [KemandirianController::class, 'getQuestions']);
    Route::post('answer-question/{keluarga_id}', [KemandirianController::class, 'answerQuestion']);
});
