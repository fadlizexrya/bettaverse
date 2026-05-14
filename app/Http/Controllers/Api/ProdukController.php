<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'List Data Produk BettaVerse',
            'data'    => $produks
        ], 200);
    }
}
