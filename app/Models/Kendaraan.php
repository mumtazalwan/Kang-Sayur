<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    public $fillable = [
        'driver_id',
        'toko_id',
        'noTelfon_cadangan',
        'jenis_kendaraan',
        'nomor_polisi',
        'nomor_rangka',
        'photo_ktp',
        'photo_kk',
        'photo_kendaraan'
    ];
}
