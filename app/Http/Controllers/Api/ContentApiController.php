<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use App\Models\Produk; // Menggunakan model Produk sebagai acuan utama
use Illuminate\Support\Str;

class ContentApiController extends Controller
{
    public function getArtikel()
    {
        $artikels = Artikel::with('user:id,name')->latest()->get()->map(function($item) {
            $item->gambar_url = $item->gambar ? asset('storage/' . $item->gambar) : null;
            return $item;
        });

        return response()->json([
            'status' => 'success',
            'data' => $artikels
        ]);
    }

    public function getMarketplace()
    {
        // Disamakan menggunakan model Produk bray, biar data yang diinput dari web maupun flutter sinkron!
        $posts = Produk::with('user:id,name')->latest()->get()->map(function($post) {
            // Membuat URL gambar absolut otomatis menuju folder public storage
            $post->foto_url = $post->foto_cupang ? asset('storage/' . $post->foto_cupang) : null;
            return $post;
        });

        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }

    public function storeMarketplace(Request $request)
    {
        // 1. Validasi input yang dikirim dari Flutter
        $request->validate([
            'nama' => 'required|string',
            'jenis' => 'required|string',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'no_wa' => 'required|string',
            'deskripsi' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Olah upload gambar
        $namaGambar = null;
        if ($request->hasFile('foto')) {
            $namaGambar = $request->file('foto')->store('produk-images', 'public');
        }

        // 3. Ambil ID User dengan fallback pengaman angka 1 jika token kosong/belum login
        $idPenjual = auth()->id() ?? 1 ;

        // 4. Simpan ke database tabel produks
        $produk = Produk::create([
            'user_id'      => $idPenjual, 
            'nama_cupang'  => $request->nama,
            'jenis_cupang' => $request->jenis,
            'harga'        => $request->harga,
            'stok'         => $request->stok,
            'foto_cupang'  => $namaGambar,
            'deskripsi'    => $request->deskripsi,
            'no_wa'        => $request->no_wa,
        ]);

        // 5. Return response sukses JSON ke Flutter
        return response()->json([
            'status' => 'success',
            'message' => 'Ikan Cupang berhasil diposting via API!',
            'data' => $produk
        ], 201);
    }
    public function storeArtikel(Request $request)
    {
        // 1. Validasi input disesuaikan dengan form Flutter kalian bray
        $request->validate([
            'judul'     => 'required|string',
            'ringkasan' => 'required|string',
            'isi'       => 'required|string',
            'gambar'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Olah upload gambar artikel
        $namaGambar = null;
            if ($request->hasFile('gambar')) {
        $namaGambar = $request->file('gambar')->store('artikel-images', 'public');
        }

        // 3. Simpan ke database sesuai kolom desc artikels;
        $artikel = Artikel::create([
             'user_id'   => auth()->id() ?? 1, // Fallback ke user id 1 jika tes tanpa token
             'judul'     => $request->judul,
             'slug'      => Str::slug($request->judul) . '-' . time(), // Membuat slug unik otomatis bray biar gak error mysql
             'ringkasan' => $request->ringkasan,
             'isi'       => $request->isi,
             'gambar'    => $namaGambar,
             'status'    => 'Published', // Set otomatis aktif
        ]);

        return response()->json([
             'status' => 'success',
             'message' => 'Artikel berhasil dibuat via API!',
             'data' => $artikel
        ], 201);
    }
    public function destroyMarketplace($id)
    {
        // Cari produk berdasarkan ID yang dikirim Flutter
        $produk = \App\Models\Produk::find($id);

       // Jika produk tidak ditemukan bray
       if (!$produk) {
             return response()->json([
                 'status' => 'error',
                 'message' => 'Produk tidak ditemukan!'
        ], 404);
        }

       // Pastikan hanya pemilik produk yang bisa menghapus (opsional tapi aman bray)
       if ($produk->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kamu tidak punya akses menghapus postingan ini'
        ], 403);
        }

       // Eksekusi hapus dari database bray!
       $produk->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Postingan marketplace berhasil dihapus!'
       ], 200);
    }
    public function updateMarketplace(Request $request, $id)
    {
       $produk = \App\Models\Produk::find($id);
       if (!$produk) {
            return response()->json(['status' => 'error', 'message' => 'Produk tidak ada'], 404);
       }

       // Ambil inputan dari Flutter
       $produk->nama_cupang = $request->input('nama_cupang', $produk->nama_cupang);
       $produk->jenis_cupang = $request->input('jenis_cupang', $produk->jenis_cupang);
       $produk->harga = $request->input('harga', $produk->harga);
       $produk->stok = $request->input('stok', $produk->stok);
       $produk->no_wa = $request->input('no_wa', $produk->no_wa);
       $produk->deskripsi = $request->input('deskripsi', $produk->deskripsi);

       // Logika ganti foto jika ada kiriman file baru dari Flutter bray
       if ($request->hasFile('foto')) {
           $file = $request->file('foto');
           $path = $file->store('marketplace', 'public');
           $produk->foto_cupang = $path;
       }

       $produk->save();
       return response()->json(['status' => 'success', 'message' => 'Berhasil diubah'], 200);
    }
    public function destroyArtikel($id)
    {
        // 1. Cari artikel berdasarkan ID yang dikirim oleh Flutter bray
        $artikel = \App\Models\Artikel::find($id);

        // 2. Jika artikel tidak ditemukan, kembalikan error 404
        if (!$artikel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Artikel tidak ditemukan'
            ], 404);
        }

        // 3. Pengaman opsional: Pastikan hanya pembuat artikel yang bisa menghapusnya
        if ($artikel->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kamu tidak punya akses untuk menghapus artikel ini!'
            ], 403);
        }

        // 4. Eksekusi hapus dari database tabel artikels
        $artikel->delete();

        // 5. Kembalikan response sukses JSON ke Flutter
        return response()->json([
            'status' => 'success',
            'message' => 'Artikel berhasil dihapus!'
        ], 200);
    }
    public function updateArtikel(Request $request, $id)
    {
        // 1. Cari artikel berdasarkan ID
        $artikel = \App\Models\Artikel::find($id);

        if (!$artikel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Artikel tidak ditemukan!'
            ], 404);
        }

        // 2. Validasi input teks biasa
        $request->validate([
            'judul' => 'required|string|max:255',
            'ringkasan' => 'required|string',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Opsional jika ingin ganti gambar
        ]);

        // 3. Update data teks
        $artikel->judul = $request->judul;
        $artikel->ringkasan = $request->ringkasan;
        $artikel->isi = $request->isi;
        $artikel->slug = \Str::slug($request->judul) . '-' . rand(1000, 9999);

        // 4. Proses upload gambar baru jika ada yang dikirim dari Flutter
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama di storage jika ada (biar VPS tidak penuh)
            if ($artikel->gambar && \Storage::disk('public')->exists($artikel->gambar)) {
                \Storage::disk('public')->delete($artikel->gambar);
            }

            // Simpan gambar baru ke folder storage/artikel-images
            $path = $request->file('gambar')->store('artikel-images', 'public');
            $artikel->gambar = $path;
        }

        // 5. Simpan semua perubahan ke database
        $artikel->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Artikel berhasil diperbarui!'
        ], 200);
    }
}
