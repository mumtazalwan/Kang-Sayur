<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_user',
        'product_id',
        'toko_id'
    ];

    protected $hidden = [
        'updated_at',
        'product_id',
        'toko_id',
        'id',
        'id_user'
    ];

    protected $table = 'reviews';
}
