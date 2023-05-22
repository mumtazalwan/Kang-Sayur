<?php

namespace App\Models;

use App\Models\Catalogue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_toko',
        'deskripsi',
        'seller_id',
        'alamat',
        'longitude',
        'latitude',
        'open',
        'close'
    ];
}
