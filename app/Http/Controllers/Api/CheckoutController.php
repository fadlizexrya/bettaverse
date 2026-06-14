<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    /**
     * Inisialisasi konfigurasi Midtrans dari config/midtrans.php.
     * Dipanggil sekali di constructor agar tidak perlu diulang di setiap method.
     */
    public function __construct()
    {
        // Ambil dari config() — BUKAN env() langsung — agar tetap bekerja
        // setelah `php artisan config:cache` di production.
        MidtransConfig::$serverKey    = config('midtrans.server_key');
        MidtransConfig::$clientKey    = config('midtrans.client_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized  = config('midtrans.is_sanitized');
        MidtransConfig::$is3ds        = config('midtrans.is_3ds');
    }

    /**
     * POST /api/checkout
     *
     * Menerima data order dari Flutter, lalu mengembalikan Snap Token
     * yang bisa dipakai untuk membuka halaman pembayaran Midtrans.
     *
     * --- Contoh Request Body (JSON) ---
     * {
     *   "order_id": "ORDER-001",       // (opsional, auto-generate jika kosong)
     *   "gross_amount": 150000,
     *   "first_name": "Budi",
     *   "last_name": "Santoso",        // (opsional)
     *   "email": "budi@example.com",
     *   "phone": "08123456789",
     *   "items": [                      // (opsional, untuk detail produk)
     *     {
     *       "id": "ITEM-1",
     *       "price": 75000,
     *       "quantity": 2,
     *       "name": "Ikan Cupang Halfmoon"
     *     }
     *   ]
     * }
     *
     * --- Contoh Response (200) ---
     * {
     *   "status": "success",
     *   "snap_token": "abc123-xyz",
     *   "redirect_url": "https://app.sandbox.midtrans.com/snap/v2/vtweb/abc123-xyz"
     * }
     */
    public function getSnapToken(Request $request)
    {
        // -----------------------------------------------------------------
        // 1. VALIDASI: Pastikan input yang masuk benar sebelum dikirim ke Midtrans
        // -----------------------------------------------------------------
        $validator = Validator::make($request->all(), [
            'gross_amount' => 'required|numeric|min:1',
            'first_name'   => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'required|string|max:20',
            'last_name'    => 'nullable|string|max:255',
            'order_id'     => 'nullable|string|max:255',
            // Validasi items array (opsional tapi kalau dikirim harus benar)
            'items'            => 'nullable|array',
            'items.*.id'       => 'required_with:items|string',
            'items.*.price'    => 'required_with:items|numeric|min:0',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.name'     => 'required_with:items|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal. Periksa kembali data yang dikirim.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // -----------------------------------------------------------------
        // 2. GUARD: Pastikan Server Key sudah diisi
        // -----------------------------------------------------------------
        if (empty(config('midtrans.server_key'))) {
            Log::error('Midtrans Server Key belum diatur di file .env');
            return response()->json([
                'status'  => 'error',
                'message' => 'Konfigurasi pembayaran belum lengkap. Hubungi admin.',
            ], 500);
        }

        // -----------------------------------------------------------------
        // 3. SUSUN PARAMETER untuk Midtrans Snap
        // -----------------------------------------------------------------
        $orderId = $request->input('order_id', 'ORDER-' . time() . '-' . mt_rand(1000, 9999));

        // gross_amount HARUS integer untuk Midtrans
        $grossAmount = (int) $request->input('gross_amount');

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $request->input('first_name'),
                'last_name'  => $request->input('last_name', ''),
                'email'      => $request->input('email'),
                'phone'      => $request->input('phone'),
            ],
        ];

        // Tambahkan item details jika dikirim dari Flutter
        if ($request->has('items') && is_array($request->input('items'))) {
            $params['item_details'] = array_map(function ($item) {
                return [
                    'id'       => $item['id'],
                    'price'    => (int) $item['price'],
                    'quantity' => (int) $item['quantity'],
                    'name'     => $item['name'],
                ];
            }, $request->input('items'));
        }

        // -----------------------------------------------------------------
        // 4. REQUEST SNAP TOKEN dari Midtrans (dibungkus try-catch)
        // -----------------------------------------------------------------
        try {
            $snapToken = Snap::getSnapToken($params);

            Log::info('Midtrans Snap Token berhasil dibuat', [
                'order_id'   => $orderId,
                'amount'     => $grossAmount,
            ]);

            return response()->json([
                'status'       => 'success',
                'snap_token'   => $snapToken,
                'redirect_url' => config('midtrans.is_production')
                    ? "https://app.midtrans.com/snap/v2/vtweb/{$snapToken}"
                    : "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}",
            ], 200);

        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token gagal dibuat', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal membuat transaksi pembayaran.',
                'debug'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
