<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'store_id',         // إضافة store_id إلى fillable
        'access_token',
        'refresh_token',
        'expire_at'
    ];
}