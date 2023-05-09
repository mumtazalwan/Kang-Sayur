<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    use HasFactory;

    public function ListProduct()
    {
        return $this->hasMany(Catalogue::class,'toko_id');
    }
}
