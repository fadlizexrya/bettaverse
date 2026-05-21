<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\MarketplacePost;
use Illuminate\Http\Request;

class ContentApiController extends Controller
{
    public function getArtikel()
    {
        $artikels = Artikel::latest()->get()->map(function($item) {
            // Kita buat URL gambar absolut supaya Flutter bisa langsung nampilin
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
        // Mengambil data dari tabel marketplace_posts beserta nama penjualnya
        $posts = MarketplacePost::with('user:id,name')->latest()->get()->map(function($post) {
            $post->foto_url = $post->foto_cupang ? asset('storage/' . $post->foto_cupang) : null;
            return $post;
        });

        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }
}
