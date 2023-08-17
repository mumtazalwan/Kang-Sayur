<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Mail\ResetPassword;
use App\Models\Address;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sandi;
use App\Models\Toko;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class AuthenticationController extends Controller
{

    public function registerAsUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'photo' => 'file|image|mimes:png,jpg,jpeg|max:3048',
            'email' => 'required|email',
            'address' => 'required',
            'phone_number' => 'required|numeric|min:10',
            'longitude' => 'required|between:-180,180',
            'latitude' => 'required|between:-90,90',
            'tanggal_lahit' => 'required|date_format:Y-m-d H:i:s',
            'password' => 'required|string|min:8'
        ]);

        $alrTaken = User::where('email', $request->email)->first();

        if ($alrTaken) {
            return response()->json([
                'message' => "akun dengan email {$alrTaken->email} sudah terdaftar"
            ]);
        } else {

            // create sandi
            $sandiId = mt_rand(1000000, 9999999);
            $sandi = Sandi::create([
                'id' => $sandiId,
                'password' => Hash::make(request('password'))
            ]);

            if ($request->photo) {
                // store photo
                $timestamp = time();
                $photoName = $timestamp . $request->photo->getClientOriginalName();
                $path = '/user_profile/' . $photoName;
                Storage::disk('public')->put($path, file_get_contents($request->photo));

                // create user
                $user = User::create([
                    'name' => request('name'),
                    'photo' => '/storage' . $path,
                    'email' => request('email'),
                    'phone_number' => request('phone_number'),
                    'address' => request('address'),
                    'latitude' => request('latitude'),
                    'longitude' => request('longitude'),
                    'tanggal_lahir' => request('tanggal_lahir'),
                    'sandi_id' => $sandiId
                ]);
            } else {
                // create user
                $user = User::create([
                    'name' => request('name'),
                    'email' => request('email'),
                    'address' => request('address'),
                    'latitude' => request('latitude'),
                    'longitude' => request('longitude'),
                    'tanggal_lahir' => request('tanggal_lahir'),
                    'sandi_id' => $sandiId
                ]);
            }

            Address::create([
                'user_id' => $user->id,
                'nama_penerima' => $user->name,
                'nomor_hp' => $user->phone_number,
                'alamat_lengkap' => $user->address,
                'longitude' => $user->longitude,
                'latitude' => $user->latitude,
                'prioritas_alamat' => "Utama",
            ]);

            // generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            // assign role to user id
            $findUser = User::findOrFail($user->id);
            $role = Role::findOrFail(1);

            $findUser->assignRole([$role]);

            return response()->json([
                'status' => 200,
                'data' => $user,
                'acces_token' => $token,
                'sandi' => $sandi
            ]);
        }
    }

    public function registerAsSeller(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'owner_name' => 'required|string',
            'photo' => 'file|image|mimes:png,jpg,jpeg|max:3048',
            'phone_number' => 'required|numeric|digits:11',
            'owner_address' => 'required',
            'store_name' => 'required|string',
            'description' => 'required',
            'store_address' => 'required',
            'store_longitude' => 'required|between:-180,180',
            'store_latitude' => 'required|between:-90,90',
            'open' => 'required|date_format:H:i',
            'close' => 'required|date_format:H:i|after:open',
        ]);

        $alrTaken = User::where('email', $request->email)->first();

        $sandiId = mt_rand(1000000000, 9999999999);

        if ($alrTaken) {
            return response()->json([
                'message' => "akun dengan email {$alrTaken->email} sudah terdaftar"
            ]);
        } else {

            if ($request->photo) {

                // store photo
                $timestamp = time();
                $photoName = $timestamp . $request->photo->getClientOriginalName();
                $path = '/user_profile/' . $photoName;
                Storage::disk('public')->put($path, file_get_contents($request->photo));
                Sandi::create([
                    'id' => $sandiId,
                    'password' => Hash::make(request('password'))
                ]);

                $user = User::create([
                    'name' => request('owner_name'),
                    'email' => request('email'),
                    'photo' => '/storage' . $path,
                    'sandi_id' => $sandiId,
                    'phone_number' => request('phone_number'),
                    'address' => request('owner_address'),
                    'longitude' => request('store_longitude'),
                    'latitude' => request('store_latitude')
                ]);

                Toko::create([
                    'nama_toko' => request('store_name'),
                    'img_profile' => '/storage' . $path,
                    'deskripsi' => request('description'),
                    'seller_id' => $user->id,
                    'alamat' => request('store_address'),
                    'longitude' => request('store_longitude'),
                    'latitude' => request('store_latitude'),
                    'open' => request('open'),
                    'close' => request('close'),

                ]);

                $token = $user->createToken('auth_token')->plainTextToken;

                $findUser = User::findOrFail($user->id);
                $role = Role::findOrFail(2);

                $findUser->assignRole([$role]);

                return response()->json([
                    'status' => 200,
                    'data' => $user,
                    'acces_token' => $token,
                ]);
            } else {
                Sandi::create([
                    'id' => $sandiId,
                    'password' => Hash::make(request('password'))
                ]);

                $user = User::create([
                    'name' => request('owner_name'),
                    'email' => request('email'),
                    'sandi_id' => $sandiId,
                    'phone_number' => request('phone_number'),
                    'address' => request('owner_address'),
                    'longitude' => request('store_longitude'),
                    'latitude' => request('store_latitude')
                ]);

                Toko::create([
                    'nama_toko' => request('store_name'),
                    'deskripsi' => request('description'),
                    'seller_id' => $user->id,
                    'alamat' => request('store_address'),
                    'longitude' => request('store_longitude'),
                    'latitude' => request('store_latitude'),
                    'open' => request('open'),
                    'close' => request('close'),

                ]);

                $token = $user->createToken('auth_token')->plainTextToken;

                $findUser = User::findOrFail($user->id);
                $role = Role::findOrFail(2);

                $findUser->assignRole([$role]);

                return response()->json([
                    'status' => 200,
                    'data' => $user,
                    'acces_token' => $token,
                ]);
            }
        }
    }

    public function registerAsDriver(Request $request)
    {
        $request->validate([
            // user
            'name' => 'required|string',
            'photo' => 'file|image|mimes:png,jpg,jpeg|max:3048',
            'phone_number' => 'required|numeric|digits:11',
            'email' => 'required|email',
            'password' => 'required|string|min:8',

            // kendaraan
            'noTelfon_cadangan' => 'required|numeric|digits:11',
            'jenis_kendaraan' => 'required',
            'nama_kendaraan' => 'required',
            'nomor_polisi' => 'required',
            'nomor_rangka' => 'required',
            'photo_ktp' => 'file|image|mimes:png,jpg,jpeg|max:3048',
            'photo_kk' => 'file|image|mimes:png,jpg,jpeg|max:3048',
            'photo_kendaraan' => 'file|image|mimes:png,jpg,jpeg|max:3048',
        ]);

        $user = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $user->id)->value('id');
        $alrTaken = User::where('email', $request->email)->first();

        if ($alrTaken) {
            return response()->json([
                'message' => "akun dengan email {$alrTaken->email} sudah terdaftar"
            ]);
        } else {

            // create sandi
            $sandiId = mt_rand(1000000, 9999999);
            $sandi = Sandi::create([
                'id' => $sandiId,
                'password' => Hash::make(request('password'))
            ]);

            if ($request->photo) {
                // store photo
                $timestamp = time();
                $photoName = $timestamp . $request->photo->getClientOriginalName();
                $path = '/user_profile/' . $photoName;
                Storage::disk('public')->put($path, file_get_contents($request->photo));

                // create user
                $user = User::create([
                    'name' => request('name'),
                    'photo' => '/storage' . $path,
                    'phone_number' => request('phone_number'),
                    'email' => request('email'),
                    'sandi_id' => $sandiId
                ]);

                // store photo ktp
                $timestamp = time();
                $photoNameKtp = $timestamp . $request->photo_ktp->getClientOriginalName();
                $pathKtp = '/user_profile/' . $photoNameKtp;
                Storage::disk('public')->put($pathKtp, file_get_contents($request->photo_ktp));

                // store photo kk
                $timestamp = time();
                $photoNameKk = $timestamp . $request->photo_kk->getClientOriginalName();
                $pathKk = '/user_profile/' . $photoNameKk;
                Storage::disk('public')->put($pathKk, file_get_contents($request->photo_kk));

                // store photo kendaraan
                $timestamp = time();
                $photoNameKendaraan = $timestamp . $request->photo_kendaraan->getClientOriginalName();
                $pathKendaraan = '/user_profile/' . $photoNameKendaraan;
                Storage::disk('public')->put($pathKendaraan, file_get_contents($request->photo_kendaraan));

                $kendaraan = Kendaraan::create([
                    'driver_id' => $user->id,
                    'toko_id' => $tokoId,
                    'noTelfon_cadangan' => request('noTelfon_cadangan'),
                    'jenis_kendaraan' => request('jenis_kendaraan'),
                    'nama_kendaraan' => request('nama_kendaraan'),
                    'nomor_polisi' => request('nomor_polisi'),
                    'nomor_rangka' => request('nomor_rangka'),
                    'photo_ktp' => '/storage' . $pathKtp,
                    'photo_kk' => '/storage' . $pathKk,
                    'photo_kendaraan' => '/storage' . $pathKendaraan,
                ]);
            } else {
                // create user
                $user = User::create([
                    'name' => request('name'),
                    'phone_number' => request('phone_number'),
                    'email' => request('email'),
                    'latitude' => request('latitude'),
                    'longitude' => request('longitude'),
                    'sandi_id' => $sandiId
                ]);

                $kendaraan = Kendaraan::create([
                    'driver_id' => $user->id,
                    'toko_id' => $tokoId,
                    'noTelfon_cadangan' => request('noTelfon_cadangan'),
                    'jenis_kendaraan' => request('jenis_kendaraan'),
                    'nama_kendaraan' => request('nama_kendaraan'),
                    'nomor_polisi' => request('nomor_polisi'),
                    'nomor_rangka' => request('nomor_rangka'),
                    'photo_ktp' => request('photo_ktp'),
                    'photo_kk' => request('photo_kk'),
                    'photo_kendaraan' => request('photo_kendaraan')
                ]);
            }

            // generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            // assign role to user id
            $findUser = User::findOrFail($user->id);
            $role = Role::findOrFail(3);

            $findUser->assignRole([$role]);

            return response()->json([
                'status' => 200,
                'data_user' => $user,
                'data_kendaraan' => $kendaraan,
                'acces_token' => $token,
                'sandi' => $sandi
            ]);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'user not found'
            ], 404);
        }

        $user_pw = Sandi::where('id', $user->sandi_id)->first();

        if ($user && Hash::check($request->password, $user_pw->password)) {
            $token = $user->createToken('user log in')->plainTextToken;

            return response()->json([
                'status' => 200,
                'message' => "user personal information",
                'data' => $user->setHidden(['id', 'sandi_id', 'longitude', 'latitude', 'created_at', 'updated_at', 'remember_token']),
                'acces_token' => $token,
                'token_type' => 'Bearer'
            ]);
        } else {
            return response()->json([
                'message' => 'password salah'
            ], 404);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function device_token_update(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'device_token' => 'required',
        ]);

        $user = User::where('email', request('email'))->first();

        $user->update([
            'device_token' => request('device_token')
        ]);

        return response()->json([
            'data' => $user
        ]);
    }

    public function sendEmailResetPassword()
    {
        $user = Auth::user();

        Mail::send(new ResetPassword($user->email, $user->name));

        return response()->json([
            'status' => 200,
            'message' => 'Email berhasil terkirim, silahkan cek email anda'
        ]);
    }
}
