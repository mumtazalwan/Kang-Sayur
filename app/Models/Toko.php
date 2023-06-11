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

    // protected $appends = [
    //     'subtotal', 'ongkir'
    // ];

    // public function getSubtotalAttribute()
    // {
    //     return $this->hasMany(Cart::class, 'toko_id')
    //         ->join('produk', 'produk.id', '=', 'carts.produk_id')
    //         ->join('variants', 'variants.product_id', '=', 'produk.id')
    //         ->select(DB::raw('sum(variants.harga_variant) as subtotal'))
    //         ->first()->subtotal;
    // }

    // public function getOngkirAttribute()
    // {
    //     $user = Auth::user();

    // $distance = $this->hasMany(Cart::class, 'toko_id')
    //     ->join('tokos', 'tokos.id', '=', 'toko_id')
    //     ->select(DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
    // * cos(radians(tokos.latitude)) 
    // * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
    // + sin(radians(" . $user->latitude . ")) 
    // * sin(radians(tokos.latitude))) as distance"))
    //     ->value('distance');

    //     return $distance * 3000;
    // }

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
        return $this
            ->hasMany(Cart::class, 'toko_id')
            ->join('variants', 'variants.id', '=', 'carts.variant_id')
            ->select(
                [
                    'user_id',
                    'toko_id',
                    'produk_id',
                    'variant_id',
                    'variant_img',
                    'variant',
                    'stok',
                    'harga_variant',
                    DB::raw('COUNT(produk_id) as inCart')
                ]
            )
            ->groupBy('produk_id', 'variant_id');
    }

    public function getProdukCheckout()
    {

        $data = $this
            ->hasMany(Cart::class, 'toko_id')
            ->join('variants', 'variants.id', '=', 'carts.variant_id')
            ->select(
                [
                    'user_id',
                    'toko_id',
                    'produk_id',
                    'variant_id',
                    'variant_img',
                    'variant',
                    'stok',
                    'harga_variant',
                    'carts.status',
                    DB::raw('COUNT(produk_id) as inCart')
                ]
            )
            ->where('status', 'true')
            ->groupBy('produk_id', 'variant_id');

        return $data;
    }
}
