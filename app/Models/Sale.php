<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Produk;

class Sale extends Model
{
    use HasFactory;

    public $fillable = [
        'session_id',
        'produk_id',
        'variant_id',
        'harga_sale',
        'stok',
        'created_at'
    ];
}
