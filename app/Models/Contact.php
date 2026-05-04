<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
public function post()
{
    return $this->belongsTo(MarketplacePost::class);
}

public function buyer()
{
    return $this->belongsTo(User::class, 'buyer_id');
}}
