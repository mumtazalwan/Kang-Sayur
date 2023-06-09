<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Review;

class Produk extends Model
{
    use HasFactory;

    public $table = 'produk';
    public $timestamps = false;

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'kategori_id',
        'katalog_id',
        'harga_produk',
        'stok_produk',
        'toko_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'kategori_id',
        'katalog_id',
        'varian_id',
        'ulasan_id',
    ];

    public function review()
    {
        return $this->hasMany(Review::class, 'product_id');
    }
}
