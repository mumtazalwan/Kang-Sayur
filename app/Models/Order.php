<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'product_id',
        'variant_id',
        'status',
        'store_id',
        'user_id',
        'created_at'
    ];

    protected $primaryKey = 'store_id';

    public function statusOrderUser()
    {
        $idUser = Auth::user();

        return $this->hasMany(Order::class, 'store_id')
            ->where('orders.status', 'Menunggu konfirmasi')
            ->where('orders.user_id', $idUser->id)
            ->join('variants', 'variants.id', '=', 'orders.variant_id')
            ->groupBy('variants.id',)
            ->select('*', DB::raw("count(variants.id) as jumlah_pembelian"));
    }

    public function statusPreparedUser()
    {
        $idUser = Auth::user();

        return $this->hasMany(Order::class, 'store_id')
            ->where('orders.status', 'Sedang disiapkan')
            ->where('orders.user_id', $idUser->id)
            ->join('variants', 'variants.id', '=', 'orders.variant_id')
            ->groupBy('variants.id')
            ->select('*', DB::raw("count(variants.id) as jumlah_pembelian"));
    }

    public function statusDeliveredUser()
    {
        $idUser = Auth::user();

        return $this->hasMany(Order::class, 'store_id')
            ->where('orders.status', 'Sedang diantar')
            ->where('orders.user_id', $idUser->id)
            ->join('variants', 'variants.id', '=', 'orders.variant_id')
            ->groupBy('variants.id')
            ->select('*', DB::raw("count(variants.id) as jumlah_pembelian"));
    }

    public function statusDoneUser()
    {
        $idUser = Auth::user();

        return $this->hasMany(Order::class, 'store_id')
            ->where('orders.status', 'Selesai')
            ->where('orders.user_id', $idUser->id)
            ->join('variants', 'variants.id', '=', 'orders.variant_id')
            ->join('produk', 'produk.id', '=', 'orders.product_id')
            ->groupBy('orders.store_id', 'orders.transaction_code')
            ->select('*', DB::raw("count(variants.id) as jumlah_pembelian"));
    }
}
