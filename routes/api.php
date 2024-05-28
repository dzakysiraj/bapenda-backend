<?php

use App\Http\Controllers\ArsipgubernurController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SuratkeluarController;
use App\Http\Controllers\SuratmasukController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::post('login', [AuthController::class, 'login']);

// Route::middleware(['auth:sanctum'])->group(function () {
//     // routes surat masuk
//     Route::get('surat-masuk', [SuratmasukController::class, 'index']);
//     Route::post('surat-masuk', [SuratmasukController::class, 'store']);
//     Route::patch('surat-masuk/{id}', [SuratmasukController::class, 'update']);
//     Route::delete('surat-masuk/{id}', [SuratmasukController::class, 'destroy']);

//     // routes surat keluar
//     Route::get('surat-keluar', [SuratkeluarController::class, 'index']);
//     Route::post('surat-keluar', [SuratkeluarController::class, 'store']);
//     Route::patch('surat-keluar/{id}', [SuratkeluarController::class, 'update']);
//     Route::delete('surat-keluar/{id}', [SuratkeluarController::class, 'destroy']);

//     // routes arsip gubernur
//     Route::get('arsip-gubernur', [ArsipgubernurController::class, 'index']);
//     Route::post('arsip-gubernur', [ArsipgubernurController::class, 'store']);
//     Route::patch('arsip-gubernur/{id}', [ArsipgubernurController::class, 'update']);
//     Route::delete('arsip-gubernur/{id}', [ArsipgubernurController::class, 'destroy']);


//     Route::get('logout', [AuthController::class, 'logout']);
// });

// Routes login
Route::post('/login', LoginController::class)->name('login');

Route::middleware(['auth:api'])->group(function () {

Route::get('/', [SuratMasukController::class, 'index']);
    // routes surat masuk
Route::get('/surat-masuk', [SuratmasukController::class, 'index']);
Route::get('/surat-masuk/{id}', [SuratmasukController::class, 'show']);
Route::post('/surat-masuk', [SuratmasukController::class, 'store']);
Route::put('/surat-masuk/{id}', [SuratmasukController::class, 'update']);
Route::delete('/surat-masuk/{id}', [SuratmasukController::class, 'destroy']);
Route::get('/search/{key}', [SuratmasukController::class, 'search']);


// routes surat keluar
Route::get('/surat-keluar', [SuratkeluarController::class, 'index']);
Route::get('/surat-keluar/{id}', [SuratkeluarController::class, 'edit']);
Route::post('/surat-keluar', [SuratkeluarController::class, 'store']);
Route::put('/surat-keluar/{id}', [SuratkeluarController::class, 'update']);
Route::delete('/surat-keluar/{id}', [SuratkeluarController::class, 'destroy']);

// routes arsip gubernur
Route::get('/arsip-gubernur', [ArsipgubernurController::class, 'index']);
Route::get('/arsip-gubernur/{id}', [ArsipgubernurController::class, 'edit']);
Route::post('/arsip-gubernur', [ArsipgubernurController::class, 'store']);
Route::put('/arsip-gubernur/{id}', [ArsipgubernurController::class, 'update']);
Route::delete('/arsip-gubernur/{id}', [ArsipgubernurController::class, 'destroy']);
});



// Route::get('logout', [AuthController::class, 'logout']);