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

    protected $appends = ['subtotal', 'ongkir'];

    public function getSubtotalAttribute()
    {
        return $this->hasMany(Cart::class, 'toko_id')
            ->join('produk', 'produk.id', '=', 'carts.produk_id')
            ->select(DB::raw('sum(produk.harga_produk) as subtotal'))
            ->first()->subtotal;
    }

    public function getOngkirAttribute()
    {
        $distance = $this->hasMany(Cart::class, 'toko_id')
            ->join('tokos', 'tokos.id', '=', 'toko_id')
            ->select(DB::raw("6371 * acos(cos(radians(" . Auth::user()->latitude . ")) 
        * cos(radians(tokos.latitude)) 
        * cos(radians(tokos.longitude) - radians(" . Auth::user()->longitude . ")) 
        + sin(radians(" . Auth::user()->latitude . ")) 
        * sin(radians(tokos.latitude))) as distance"))
            ->first()->distance;

        return $distance * 3000;
    }

    protected $fillable = [
        'nama_toko',
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
        return $this->hasMany(Cart::class, 'toko_id');
    }
}
