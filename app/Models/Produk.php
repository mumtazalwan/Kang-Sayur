<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Review;

class Produk extends Model
{
    use HasFactory;

    public $table = 'produk';

    public function review()
    {
        return $this->hasMany(Review::class, 'product_id');
    }
}
