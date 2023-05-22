<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class kategori extends Model
{
    use HasFactory;

    public $table = 'kategori';

    protected $fillable = [
        'nama_kategori'
    ];


    public function kategori()
    {
        return $this->hasMany(Kategori::class, 'kategori_id');
    }
}
