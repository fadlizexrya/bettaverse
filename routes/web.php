<?php

use App\Http\Controllers\ArtikelController;

// ✅ VIEW (buat tampilan)
Route::get('/artikel', [ArtikelController::class, 'view']);

// ✅ API (biar tetap ada)
Route::get('/api/artikel/{id}', [ArtikelController::class, 'show']);
Route::post('/api/artikel', [ArtikelController::class, 'store']);
Route::get('/', function () {
    return view('welcome');
});