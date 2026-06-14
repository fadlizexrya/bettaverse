<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk integrasi payment gateway Midtrans.
    | Semua value diambil dari file .env agar aman dan mudah diubah.
    |
    */

    'merchant_id'   => env('MIDTRANS_MERCHANT_ID'),
    'client_key'    => env('MIDTRANS_CLIENT_KEY'),
    'server_key'    => env('MIDTRANS_SERVER_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    // Aktifkan sanitasi parameter agar Midtrans membersihkan input otomatis
    'is_sanitized'  => true,

    // Aktifkan 3DS (3D Secure) untuk keamanan transaksi kartu kredit
    'is_3ds'        => true,

];
