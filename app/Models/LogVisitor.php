<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogVisitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'toko_id'
    ];

    public $table = "log_visitor";
}
