<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi input yang dikirim dari Flutter
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:user,seller' // Memastikan role hanya boleh 'user' atau 'seller'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Input tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Cari user berdasarkan email di database
        $user = User::where('email', $request->email)->first();

        // 3. Validasi apakah user ada dan password-nya cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // 4. FIX BUG: Validasi apakah role di DB sesuai dengan halaman login di Flutter
        if ($user->role !== $request->role) {
            $roleLabel = $request->role === 'user' ? 'Pengguna' : 'Penjual';
            return response()->json([
                'status' => 'error',
                'message' => 'Akun Anda tidak terdaftar sebagai ' . $roleLabel . '.'
            ], 403); // 403 Forbidden (Ditolak karena beda role)
        }

        // 5. Buat token (Menggunakan Laravel Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 6. Return response sukses sesuai kebutuhan di login_page.dart kamu
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
}
