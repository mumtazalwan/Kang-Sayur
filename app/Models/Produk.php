<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Review;
use Illuminate\Support\Facades\DB;

class Produk extends Model
{
    use HasFactory;

    public $table = 'produk';

    protected $appends = ['image', 'harga'];

    public function getImageAttribute()
    {
        return $variant_img = DB::table('variants')
            ->join('produk', 'produk.id', '=', 'variants.product_id')
            ->select('variants.variant_img as img')
            ->orderBy('variants.id', 'ASC')
            ->value('img');
    }

    public function getHargaAttribute()
    {
        return $variant_harga = DB::table('variants')
            ->join('produk', 'produk.id', '=', 'variants.product_id')
            ->select('variants.harga_variant as harga')
            ->orderBy('variants.id', 'ASC')
            ->value('harga');
    }

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

    public function variant()
    {
        return $this->hasMany(Variant::class, 'product_id');
    }

    public function review()
    {
        return $this->hasMany(Review::class, 'product_id')->join('users', 'users.id', '=', 'reviews.id_user');
    }
}
