<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreAdvertising extends Model
{
    use HasFactory;

    public $fillable = [
        'toko_id',
        'img_pamflet',
        'expire_through',
    ];
}
