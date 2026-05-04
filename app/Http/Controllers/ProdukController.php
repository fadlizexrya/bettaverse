<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class ProdukController extends Controller
{
    // ================= LIST PRODUK =================
    public function index()
    {
        $produks = Produk::latest()->get();
        return view('produk.index', compact('produks'));
    }

    // ================= FORM CREATE =================
    public function create()
    {
        return view('produk.create');
    }

    // ================= SIMPAN PRODUK =================
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        Produk::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'user_id' => auth()->id() ?? 1
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    // ================= DETAIL PRODUK + COD =================
    public function show($id)
    {
        $produk = Produk::with('user')->findOrFail($id);

        $buyer = auth()->user();
        $seller = $produk->user;

        $cod = false;
        $jarak = null;

        if ($buyer && $seller && $buyer->latitude && $seller->latitude) {
            $jarak = $this->hitungJarak(
                $buyer->latitude,
                $buyer->longitude,
                $seller->latitude,
                $seller->longitude
            );

            $cod = $jarak <= 5; // radius 5 km
        }

        return view('produk.show', compact('produk', 'cod', 'jarak'));
    }

    // ================= FORM EDIT =================
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        $produk = Produk::findOrFail($id);

        $produk->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate');
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }

    // ================= HITUNG JARAK (HAVERSINE) =================
    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return round($earthRadius * $c, 2);
    }
}