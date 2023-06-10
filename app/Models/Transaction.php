<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;

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

    public function statusOrder()
    {
        return $this->hasMany(Order::class, 'transaction_code')->where('orders.status', 'Menunggu konfirmasi');
    }

    public function statusPrepared()
    {
        return $this->hasMany(Order::class, 'transaction_code')->where('orders.status', 'Sedang disiapkan');
    }

    public function statusReadyDelivered()
    {
        return $this->hasMany(Order::class, 'transaction_code')->where('orders.status', 'Menunggu driver');
    }

    public function statusDelivered()
    {
        return $this->hasMany(Order::class, 'transaction_code')->where('orders.status', 'Sedang diantar');
    }

    public function statusDone()
    {
        return $this->hasMany(Order::class, 'transaction_code')->where('orders.status', 'Selesai');
    }
}
