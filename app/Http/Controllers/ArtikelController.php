<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth; 

class ArtikelController extends Controller
{
    public function view()
    {
        $artikels = Artikel::latest()->get();
        return view('artikel.index', compact('artikels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $namaGambar = null;
        if ($request->hasFile('gambar')) {
            $namaGambar = $request->file('gambar')->store('artikel-images', 'public');
        }

        Artikel::create([
            'judul'     => $request->judul,
            'isi'       => $request->isi,
            'gambar'    => $namaGambar,
            'status'    => 'published',
            'user_id'   => Auth::id(),
            'slug'      => Str::slug($request->judul),
            'ringkasan' => Str::limit(strip_tags($request->isi), 150),
        ]);

        return redirect()->back()->with('success', 'Artikel BettaVerse berhasil disimpan!');
    }

    // --- TAMBAHKAN INI DI SINI ---
    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);
        
        // Hapus gambar dari storage jika ada
        if ($artikel->gambar) {
            \Storage::disk('public')->delete($artikel->gambar);
        }

        $artikel->delete();

        return redirect()->back()->with('success', 'Artikel berhasil dihapus!');
    }
}
