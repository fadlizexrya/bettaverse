<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function view()
    {
        $artikels = Artikel::latest()->get();
        return view('artikel.index', compact('artikels'));
    }
}