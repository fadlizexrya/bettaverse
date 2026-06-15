<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ContentApiController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post("/login", [AuthApiController::class, "login"]);
Route::post("/register", [AuthApiController::class, "register"]);

// Content
Route::get("/artikel", [ContentApiController::class, "getArtikel"]);
Route::get("/marketplace", [ContentApiController::class, "getMarketplace"]);

// Checkout / Pembayaran Midtrans (tanpa auth untuk testing)
Route::post("/checkout", [CheckoutController::class, "getSnapToken"]);

// Riwayat Transaksi (tanpa auth untuk testing, nanti bisa dipindahkan ke dalam sanctum)
Route::get("/transactions", [CheckoutController::class, "getTransactions"]);

// Webhook Midtrans — dipanggil langsung oleh server Midtrans,
// WAJIB di luar auth:sanctum agar bisa diakses tanpa Bearer Token.
Route::post("/webhook/midtrans", [CheckoutController::class, "notificationCallback"]);

// Protected routes (membutuhkan Bearer Token dari login)
Route::middleware("auth:sanctum")->group(function () {
    // Profile
    Route::get("/user-profile", [AuthApiController::class, "profile"]);
    Route::post("/update-profile", [AuthApiController::class, "updateProfile"]);
});

