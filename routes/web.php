<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/privacy-policy', function () {
   return view('privacy-policy');
});

Route::prefix('keluarga')->middleware('auth')->group(function(){
    Route::get('/export/bulk', [App\Http\Controllers\WebExportController::class, 'exportKeluargaBulk'])->name('keluarga.export.bulk');
    Route::get('/export/single/{keluarga_id}', [App\Http\Controllers\WebExportController::class, 'exportKeluargaById'])->name('keluarga.export.single');
});
