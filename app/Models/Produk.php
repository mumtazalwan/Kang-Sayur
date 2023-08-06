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

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'kategori_id',
        'toko_id',
        'ulasan_id',
        'is_onsale'
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
        return $this->hasMany(Review::class, 'product_id')
            ->join('users', 'users.id', '=', 'reviews.id_user')
            ->select([
                'reviews.*',
                'users.name as name_user',
                'users.photo as gambar_user'
            ]);
    }
}
