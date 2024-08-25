<?php

use App\Http\Controllers\AnakSakitController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeluargaController;
use App\Http\Controllers\KemandirianController;
use App\Http\Controllers\KesehatanLingkunganController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\PuskesmasController;
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

// Puskesmas
Route::prefix('puskesmas')->group(function () {
    Route::get('/list', [PuskesmasController::class, 'getList']);
});

Route::middleware('auth:sanctum')->prefix('operator')->group(function(){
    // Home
    Route::get('/home', [OperatorController::class, 'home']);
    // Approval
    Route::get('/approval/detail/{keluarga_id}', [OperatorController::class, 'detailRequest']);
    Route::put('/approve/{keluarga_id}', [OperatorController::class, 'approveKeluarga']);
});

// Keluarga
Route::prefix('keluarga')->group(function () {
    Route::get('/find', [KeluargaController::class, 'findNIK']);
    Route::post('/register', [KeluargaController::class, 'register']);

    // Home
    Route::get('/home/{keluarga_id}', [KeluargaController::class, 'homeData']);
});

// Active isApproved Profile
Route::middleware('isApproved')->group(function(){
    // Kemandirian
    Route::prefix('kemandirian')->group(function(){
        // Get Data Bleum
        Route::get('/available/{keluarga_id}', [KemandirianController::class, 'availableToNextTest']);
        Route::get('/questions/{keluarga_id}', [KemandirianController::class, 'getQuestions']);
        Route::post('answer-question/{keluarga_id}', [KemandirianController::class, 'answerQuestion']);
    });

    // Anak Sakit
    Route::prefix('anak-sakit')->group(function(){
        Route::get('get/{keluarga_id}', [AnakSakitController::class, 'getAnakSakit']);
        Route::get('/penyakit-list/{keluarga_id}', [AnakSakitController::class, 'getPenyakitList']);
        Route::post('store-anak-sakit/{keluarga_id}', [AnakSakitController::class, 'storeAnakSakit']);
    });

    // Kesehatan Lingkungan (Observasi Sanitasi)
    Route::prefix('kesehatan-lingkungan')->group(function(){
        Route::get('get/{keluarga_id}', [KesehatanLingkunganController::class, 'getByKeluarga']);
        Route::get('detail/{keluarga_id}/{id}', [KesehatanLingkunganController::class, 'getById']);
        Route::get('/questions/{keluarga_id}', [KesehatanLingkunganController::class, 'getQuestions']);
        Route::post('store/{keluarga_id}', [KesehatanLingkunganController::class, 'storeKesehatanLingkungan']);
    });
});
