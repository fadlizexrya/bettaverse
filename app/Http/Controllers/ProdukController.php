<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::latest()->get();
        return view('produk.index', compact('produks'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi input (Semua jadi required agar data lengkap)
        $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'no_wa' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // 2. Olah Gambar
        $namaGambar = null;
        if ($request->hasFile('gambar')) {
            $namaGambar = $request->file('gambar')->store('produk-images', 'public');
        }

        // 3. Simpan ke Database
        Produk::create([
            'user_id'      => Auth::id(),
            'nama_cupang'  => $request->nama,
            'jenis_cupang' => $request->jenis, // Diambil dari input 'jenis'
            'harga'        => $request->harga,
            'stok'         => $request->stok,
            'foto_cupang'  => $namaGambar,
            'deskripsi'    => $request->deskripsi,
            'no_wa'        => $request->no_wa, // Diambil dari input 'no_wa'
        ]);

        return redirect()->route('produk.index')->with('success', 'Ikan Cupang berhasil diposting!');
    }

    // ================= FITUR HAPUS =================
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        
        // Opsional: Hapus file gambar di storage jika ada
        if ($produk->foto_cupang) {
            \Storage::disk('public')->delete($produk->foto_cupang);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }

    // Fungsi lain (show, edit, update) tetap sama...
}
