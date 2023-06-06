<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Produk;

class Sale extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
