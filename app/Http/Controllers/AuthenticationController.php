<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sandi;
use App\Models\Toko;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;

class AuthenticationController extends Controller
{

    public function registerAsUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $alrTaken = User::where('email', $request->email)->first();

        if ($alrTaken) {
            return response()->json([
                'message' => "akun dengan email {$alrTaken->email} sudah terdaftar"
            ]);
        } else {
            $sandiId = mt_rand(1000000, 9999999);

            $sandi = Sandi::create([
                'id' => $sandiId,
                'password' => Hash::make(request('password'))
            ]);

            $user = User::create([
                'name' => request('name'),
                'email' => request('email'),
                'sandi_id' => $sandiId
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            $findUser = User::findOrFail($user->id);
            $role = Role::findOrFail(1);

            $findUser->assignRole([$role]);

            return response()->json([
                'data' => $user, 'acces_token' => $token, 'sandi' => $sandi, 'token_type' => 'Bearer'
            ]);
        }
    }

    public function registerAsSeller(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'owner_name' => 'required|string',
            'phone_number' => 'required|numeric|digits:11',
            'owner_address' => 'required',
            'owner_longitude' => 'required|between:-180,180',
            'owner_latitude' => 'required|between:-90,90',
            'store_name' => 'required|string',
            'description' => 'required',
            'store_address' => 'required',
            'store_longitude' => 'required|between:-180,180',
            'store_latitude' => 'required|between:-90,90',
            'open' => 'required|date_format:H:i:s',
            'close' => 'required|date_format:H:i:s|after:open',

        ]);

        $alrTaken = User::where('email', $request->email)->first();

        $sandiId = mt_rand(1000000000, 9999999999);

        if ($alrTaken) {
            return response()->json([
                'message' => "akun dengan email {$alrTaken->email} sudah terdaftar"
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
                'longitude' => request('owner_longitude'),
                'latitude' => request('owner_latitude')
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
                'data' => $user,
                'acces_token' => $token,
            ]);
        }
    }

    public function registerAsDriver(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $alrTaken = User::where('email', $request->email)->first();

        $sandiId = mt_rand(1000000000, 9999999999);

        if ($alrTaken) {
            return response()->json([
                'message' => "akun dengan email {$alrTaken->email} sudah terdaftar"
            ]);
        } else {

            $sandi = Sandi::create([
                'id' => $sandiId,
                'password' => Hash::make(request('password'))
            ]);

            $user = User::create([
                'name' => request('name'),
                'email' => request('email'),
                'sandi_id' => $sandiId
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            $findUser = User::findOrFail($user->id);
            $role = Role::findOrFail(3);

            $findUser->assignRole([$role]);

            return response()->json([
                'data' => $user, 'acces_token' => $token, 'sandi' => $sandi, 'token_type' => 'Bearer'
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
                'data' => $user,
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
}
