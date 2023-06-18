<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        "user_id",
        "payment_method",
        'transaction_token',
        'client_key',
        "notes"
    ];

    protected $primaryKey = "transaction_code";


    public function statusOrder()
    {
        $dataUser = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $dataUser->id)->value($dataUser->id);

        return $this->hasMany(Order::class, 'transaction_code')->where('orders.status', 'Menunggu konfirmasi')->where('orders.store_id', $tokoId);
    }

    public function statusPrepared()
    {
        $dataUser = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $dataUser->id)->value($dataUser->id);

        return $this->hasMany(Order::class, 'transaction_code')->where('orders.status', 'Sedang disiapkan')->where('orders.store_id', $tokoId);
    }

    public function statusReadyDelivered()
    {
        $dataUser = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $dataUser->id)->value($dataUser->id);

        return $this->hasMany(Order::class, 'transaction_code')->where('orders.status', 'Menunggu driver')->where('orders.store_id', $tokoId);
    }

    public function statusDelivered()
    {
        $dataUser = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $dataUser->id)->value($dataUser->id);

        return $this->hasMany(Order::class, 'transaction_code')->where('orders.status', 'Sedang diantar')->where('orders.store_id', $tokoId);
    }

    public function statusDone()
    {
        $dataUser = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $dataUser->id)->value($dataUser->id);

        return $this->hasMany(Order::class, 'transaction_code')->where('orders.status', 'Selesai')->where('orders.store_id', $tokoId);
    }
}
