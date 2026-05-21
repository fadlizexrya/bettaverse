<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    // --- FIXED LOGIKA REGISTER ---
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
            'address' => 'required|string',
            'role' => 'required|in:user,seller',
            'shop_name' => 'required_if:role,seller|nullable|string|max:255',
        ], [
	    'shop_name.required_if' => 'Nama Toko Wajib DIISI!',
	]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan data baru ke database dengan role yang dikirim dinamis dari Flutter
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role, // Memastikan masuk sebagai 'seller' atau 'user'
            'shop_name' => $request->role === 'seller' ? $request->shop_name : null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi Berhasil! Silakan Login.',
            'user' => $user
        ], 201);
    }

    // --- FIXED LOGIKA LOGIN ---
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:user,seller' // Memastikan role dikirim dari Flutter
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Input tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        // Cek email dan password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // FIX BUG UTAMA: Menolak login jika role di DB tidak cocok dengan form login di Flutter
        if ($user->role !== $request->role) {
            $roleLabel = $request->role === 'user' ? 'Pengguna' : 'Penjual';
            return response()->json([
                'status' => 'error',
                'message' => 'Akun Anda tidak terdaftar sebagai ' . $roleLabel . '.'
            ], 403); 
        }

        // Buat token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ], 200);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }
    // --- TAMBAHKAN FUNGSI INI DI PALING BAWAH CONTROLLER ---
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'address' => 'required|string',
            'shop_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update data ke database (menggunakan field 'address' sesuai struktur table-mu)
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'shop_name' => $user->role === 'seller' ? $request->shop_name : null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil diperbarui',
            'user' => $user
        ], 200);
    }
}
