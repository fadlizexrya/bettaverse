<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplacePost extends Model
{
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
    public function user()
    {
    return $this->belongsTo(User::class);
    }
}
