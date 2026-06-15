<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
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
     * GET /api/transactions
     *
     * Mengembalikan daftar semua transaksi, diurutkan dari yang terbaru.
     * Untuk production, sebaiknya filter berdasarkan user yang login.
     *
     * --- Contoh Response (200) ---
     * {
     *   "status": "success",
     *   "data": [
     *     {
     *       "id": 1,
     *       "order_id": "ORDER-1718344800-1234",
     *       "gross_amount": 150000,
     *       "status": "pending",
     *       "payment_type": null,
     *       "created_at": "2026-06-14T05:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function getTransactions(Request $request)
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data'   => $transactions,
        ], 200);
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

            // -------------------------------------------------------------
            // 5. SIMPAN TRANSAKSI ke database dengan status 'pending'
            // -------------------------------------------------------------
            Transaction::create([
                'user_id'      => $request->user()?->id, // null jika tanpa auth
                'order_id'     => $orderId,
                'gross_amount' => $grossAmount,
                'status'       => 'pending',
                'snap_token'   => $snapToken,
            ]);

            Log::info('Midtrans Snap Token berhasil dibuat & transaksi disimpan', [
                'order_id'   => $orderId,
                'amount'     => $grossAmount,
            ]);

            return response()->json([
                'status'       => 'success',
                'snap_token'   => $snapToken,
                'order_id'     => $orderId,
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

    /**
     * POST /api/webhook/midtrans
     *
     * Endpoint untuk menerima notifikasi (webhook) dari Midtrans.
     * Route ini HARUS di luar middleware auth:sanctum karena dipanggil
     * langsung oleh server Midtrans, bukan oleh user/Flutter.
     *
     * Midtrans mengirim JSON body seperti:
     * {
     *   "transaction_time": "2026-06-14 12:00:00",
     *   "transaction_status": "settlement",
     *   "transaction_id": "xxx-xxx-xxx",
     *   "status_message": "midtrans payment notification",
     *   "status_code": "200",
     *   "signature_key": "sha512hash...",
     *   "payment_type": "bank_transfer",
     *   "order_id": "ORDER-1718344800-1234",
     *   "merchant_id": "M844777922",
     *   "gross_amount": "150000.00",
     *   "fraud_status": "accept",
     *   ...
     * }
     *
     * Langkah verifikasi:
     * 1. Hitung signature = SHA512(order_id + status_code + gross_amount + server_key)
     * 2. Bandingkan dengan signature_key dari body
     * 3. Jika cocok, update status transaksi di database
     */
    public function notificationCallback(Request $request)
    {
        // -----------------------------------------------------------------
        // 1. AMBIL DATA dari body webhook
        // -----------------------------------------------------------------
        $orderId           = $request->input('order_id');
        $statusCode        = $request->input('status_code');
        $grossAmount       = $request->input('gross_amount');
        $transactionStatus = $request->input('transaction_status');
        $paymentType       = $request->input('payment_type');
        $fraudStatus       = $request->input('fraud_status');
        $signatureKey      = $request->input('signature_key');
        $serverKey         = config('midtrans.server_key');

        Log::info('Midtrans Webhook diterima', [
            'order_id'           => $orderId,
            'transaction_status' => $transactionStatus,
            'status_code'        => $statusCode,
            'payment_type'       => $paymentType,
        ]);

        // -----------------------------------------------------------------
        // 2. VALIDASI: Pastikan field wajib ada
        // -----------------------------------------------------------------
        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            Log::warning('Midtrans Webhook: field wajib tidak lengkap', $request->all());
            return response()->json(['message' => 'Invalid notification data'], 400);
        }

        // -----------------------------------------------------------------
        // 3. VERIFIKASI SIGNATURE KEY
        //    Formula: SHA512(order_id + status_code + gross_amount + server_key)
        // -----------------------------------------------------------------
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($expectedSignature !== $signatureKey) {
            Log::warning('Midtrans Webhook: Signature key tidak valid', [
                'order_id' => $orderId,
                'expected' => $expectedSignature,
                'received' => $signatureKey,
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // -----------------------------------------------------------------
        // 4. CARI TRANSAKSI di database
        // -----------------------------------------------------------------
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            Log::warning('Midtrans Webhook: Transaksi tidak ditemukan', ['order_id' => $orderId]);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // -----------------------------------------------------------------
        // 5. UPDATE STATUS berdasarkan transaction_status dari Midtrans
        //
        //    Mapping status Midtrans:
        //    - 'capture'    → Hanya untuk kartu kredit. Cek fraud_status.
        //    - 'settlement' → Pembayaran berhasil (final).
        //    - 'pending'    → Menunggu pembayaran.
        //    - 'deny'       → Ditolak oleh bank/fraud detection.
        //    - 'expire'     → Kadaluarsa, user tidak bayar tepat waktu.
        //    - 'cancel'     → Dibatalkan (oleh merchant atau sistem).
        // -----------------------------------------------------------------
        $newStatus = match ($transactionStatus) {
            'capture' => ($fraudStatus === 'accept') ? 'settlement' : 'fraud',
            'settlement' => 'settlement',
            'pending' => 'pending',
            'deny' => 'deny',
            'expire' => 'expire',
            'cancel' => 'cancel',
            default => $transactionStatus, // fallback: simpan apa adanya
        };

        $transaction->update([
            'status'       => $newStatus,
            'payment_type' => $paymentType,
        ]);

        Log::info('Midtrans Webhook: Status transaksi diperbarui', [
            'order_id'   => $orderId,
            'old_status' => $transaction->getOriginal('status'),
            'new_status' => $newStatus,
        ]);

        // Midtrans mengharapkan response 200 OK agar tidak mengirim ulang notifikasi
        return response()->json(['message' => 'OK'], 200);
    }
}
