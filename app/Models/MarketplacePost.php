<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplacePost extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'harga',
        'stok',
    ];

    // ✅ relasi ke penjual
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ✅ relasi ke yang menghubungi
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'post_id');
    }
}