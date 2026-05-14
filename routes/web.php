<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\ProdukController; // Tambahkan ini juga

Route::redirect('/login', '/admin/login')->name('login');

// ================= ARTIKEL =================
Route::get('/artikel', [ArtikelController::class, 'view'])->name('artikel.index');
Route::get('/artikel/create', function () {
    return view('artikel.create');
})->name('artikel.create');

// Gunakan /artikel (tanpa s) agar rapi dan beri nama artikel.store
Route::post('/artikel', [ArtikelController::class, 'store'])->name('artikel.store');

Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy'])->name('artikel.destroy');


// ================= PRODUK (MARKETPLACE) =================
// Jangan lupa tambahkan route untuk produk kamu!
Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');


// ================= API =================
Route::post('/api/artikel', [ArtikelController::class, 'store']);
