<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;

class ArtikelController extends Controller {
    public function index() {
        $artikels = Artikel::latest()->get();
        return response()->json(['data' => $artikels]);
    }
}
