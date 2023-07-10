<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAdvertising extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'expire_through' => "datetime:Y-m-d\TH:iPZ",
    ];
}
