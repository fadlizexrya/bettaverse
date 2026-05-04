<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikels';

    protected $fillable = [
        'judul',
        'isi',
        'gambar',
        'status',
        'user_id',
    ];

    // ✅ relasi ke user (penulis)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ relasi ke komentar (PAKAI INI SAJA)
    public function komentarArtikels()
{
    return $this->hasMany(\App\Models\KomentarArtikel::class, 'artikel_id');
}
public function komentars()
{
    return $this->hasMany(KomentarArtikel::class, 'artikel_id');
}
}