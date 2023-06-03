<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        "user_id",
        "payment_method",
        "notes"
    ];

    public function getProductCart()
    {
        $user = Auth::user();
        // $tokoId = Toko::where('seller_id', $user->id)->first();

        return $this->hasMany(Order::class, 'transaction_code')->where('orders.status', 'Menunggu pembayaran');
    }
}