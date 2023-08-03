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
        'notes',
        'status_diulas',
        'store_id',
        'user_id',
        'created_at'
    ];
}
