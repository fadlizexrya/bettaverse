<?php

use App\Http\Controllers\ArtikelController;

Route::get('/artikel', [ArtikelController::class, 'index']);
Route::post('/artikel', [ArtikelController::class, 'store']);