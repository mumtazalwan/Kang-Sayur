<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'nama_penerima',
        'nomor_hp',
        'alamat_lengkap',
        'longitude',
        'latitude',
        'label_alamat',
        'prioritas_alamat',
        'catatan'
    ];
}
