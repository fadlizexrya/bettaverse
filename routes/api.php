<?php

use Illuminate\Support\Facades\Route; 
//use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\ArtikelController as ApiArtikelController;

Route::get('/artikel', [ApiArtikelController::class, 'index']);
Route::get('/produks', [ProdukController::class, 'index']);
//Route::get('/artikel', [ArtikelController::class, 'index']);
Route::post('/artikel', [ArtikelController::class, 'store']);
