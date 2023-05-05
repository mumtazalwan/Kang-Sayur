<?php

namespace App\Models;

use App\Models\Katalog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    public function katalog()
    {
        return $this->hasMany(Katalog::class, "id_katalog");
    }
}
