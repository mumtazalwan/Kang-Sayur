<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Kategori;

class Kategori extends Model
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
