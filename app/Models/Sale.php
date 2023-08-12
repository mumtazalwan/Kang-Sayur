<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Produk;

class Sale extends Model
{
    use HasFactory;

//    public function product()
//    {
//        return $this->belongsTo(Produk::class, 'produk_id')
//            ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
//            ->join('variants', 'variants.product_id', '=', 'produk.id')
//            ->select('produk.id',
//                'produk.nama_produk',
//                'tokos.id as toko_id',
//                'tokos.nama_toko',
//                'tokos.img_profile as profile_toko',
//                'variants.id as variant_id',
//            );
//    }
}
