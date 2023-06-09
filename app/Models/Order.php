<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
