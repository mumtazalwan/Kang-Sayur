<?php

namespace App\Models;

use App\Models\Catalogue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    public function Catalogue()
    {
        return $this->hasMany(Catalogue::class,'toko_id');
    }
}
