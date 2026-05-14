<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    // Menghubungkan Model Produk ke tabel marketplace_posts
    protected $table = 'marketplace_posts';

    protected $fillable = [
        'user_id',
        'nama_cupang',
        'jenis_cupang',
        'harga',
        'stok',
        'foto_cupang',
        'deskripsi',
        'no_wa',
    ];

    // Relasi ke User (Penjual)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
