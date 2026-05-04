<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomentarArtikel extends Model
{
    protected $table = 'komentar_artikels';

    protected $fillable = [
        'artikel_id',
        'user_id',
        'komentar'
    ];

    public function artikel()
{
    return $this->belongsTo(\App\Models\Artikel::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}
}