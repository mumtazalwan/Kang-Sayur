<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Sandi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    // protected $appends = ['subtotal', 'ongkir'];

    // public function getSubtotalAttribute()
    // {
    //     return $this->hasMany(Cart::class, 'toko_id')
    //         ->join('produk', 'produk.id', '=', 'carts.produk_id')
    //         ->select(DB::raw('sum(produk.harga_produk) as subtotal'))
    //         ->first()->subtotal;
    // }

    // public function getOngkirAttribute()
    // {
    //     $distance = $this->hasMany(Cart::class, 'toko_id')
    //         ->join('tokos', 'tokos.id', '=', 'toko_id')
    //         ->select(DB::raw("6371 * acos(cos(radians(" . Auth::user()->latitude . ")) 
    //     * cos(radians(tokos.latitude)) 
    //     * cos(radians(tokos.longitude) - radians(" . Auth::user()->longitude . ")) 
    //     + sin(radians(" . Auth::user()->latitude . ")) 
    //     * sin(radians(tokos.latitude))) as distance"))
    //         ->first()->distance;

    //     return $distance * 3000;
    // }

    public function getProductCart()
    {
        // $user = Auth::user();
        // $tokoId = Toko::where('seller_id', $user->id)->first();

        return $this->whereHas(Order::class, 'user_id');
    }
}
