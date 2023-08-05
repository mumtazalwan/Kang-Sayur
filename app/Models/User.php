<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Sandi;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public $timestamps = false;
    // protected $appends = ['link_foto'];

    // public function getLinkFotoAttribute()
    // {
    //     if ($this->photo) {
    //         return url('/storage/' . $this->photo);
    //     } else {
    //         return url('/storage/profile/userdefault.png');
    //     }
    // }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'photo',
        'email',
        'password',
        'sandi_id',
        'phone_number',
        'tanggal_lahir',
        'address',
        'longitude',
        'latitude'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sandi()
    {
        return $this->hasOne(Sandi::class, 'sandi_id');
    }
}
