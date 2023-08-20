<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    public $fillable = [
        'person_one',
        'person_two',
        'updated_at'
    ];
}
