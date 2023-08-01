<?php

namespace App\Models;

use App\Models\Catalogue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Toko extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_toko',
        'img_profile',
        'deskripsi',
        'seller_id',
        'alamat',
        'longitude',
        'latitude',
        'open',
        'close'
    ];

    public function getProductCart()
    {
        $dataUser = Auth::user();

        return $this
            ->hasMany(Cart::class, 'toko_id')
            ->join('variants', 'variants.id', '=', 'carts.variant_id')
            ->join('produk', 'produk.id', '=', 'carts.produk_id')
            ->where('carts.user_id', $dataUser->id)
            ->select(
                [
                    'produk.id as produk_id',
                    'produk.nama_produk',
                    'user_id',
                    'carts.toko_id',
                    'produk_id',
                    'variant_id',
                    'variant_img',
                    'variant',
                    'stok',
                    'status',
                    'harga_variant',
                    DB::raw('COUNT(produk_id) as inCart')
                ]
            )
            ->groupBy('carts.produk_id', 'variant_id')
            ->orderBy('carts.created_at',  "DESC");
    }

    public function getProdukCheckout()
    {
        $dataUser = Auth::user();

        $data = $this
            ->hasMany(Cart::class, 'toko_id')
            ->join('variants', 'variants.id', '=', 'carts.variant_id')
            ->join('produk', 'produk.id', '=', 'carts.produk_id')
            ->where('carts.user_id', $dataUser->id)
            ->select(
                [
                    'produk.id as produk_id',
                    'produk.nama_produk',
                    'user_id',
                    'carts.toko_id',
                    'produk_id',
                    'variant_id',
                    'variant_img',
                    'variant',
                    'stok',
                    'status',
                    'harga_variant',
                    DB::raw('COUNT(produk_id) as inCart')
                ]
            )
            ->where('status', 'true')
            ->groupBy('carts.produk_id', 'variant_id')
            ->orderBy('carts.created_at',  "DESC");

        return $data;
    }
}
