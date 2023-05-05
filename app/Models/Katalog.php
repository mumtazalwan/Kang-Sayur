<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Produk;

class Katalog extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Produk::class, 'katalog_id');
    }
}
