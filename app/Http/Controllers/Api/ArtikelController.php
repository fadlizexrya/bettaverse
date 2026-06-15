<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;

class ArtikelController extends Controller {
    public function index() {
        $artikels = Artikel::with('user')->latest()->get();
        return response()->json(['data' => $artikels]);
    }
	public function store(Request $request) {
        try {
            // 1. Validasi input data dari Flutter
            $request->validate([
                'judul' => 'required|string',
                'ringkasan' => 'nullable|string',
                'isi' => 'required|string',
                'user_id' => 'required',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // 2. Handle upload gambar ke folder public storage jika ada
            $namaGambar = null;
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                // Otomatis tersimpan ke folder storage/app/public/artikel
                $path = $file->store('artikel', 'public'); 
                $namaGambar = $path; 
            }

            // 3. Simpan data baru ke dalam database table artikels
            $artikel = Artikel::create([
                'judul' => $request->judul,
                'ringkasan' => $request->ringkasan,
                'isi' => $request->isi,
                'user_id' => $request->user_id,
                'gambar' => $namaGambar,
                'likes_count' => 0,
                'dislikes_count' => 0,
            ]);

            // 4. Berikan respon sukses JSON ke Flutter
            return response()->json([
                'success' => true,
                'message' => 'Artikel berhasil disimpan!',
                'data' => $artikel
            ], 201);

        } catch (\Exception $e) {
            // Jika ada masalah (misal database field kurang), kirim pesan errornya
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan artikel: ' . $e->getMessage()
            ], 500);
        }
    }
}
