<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sandi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

        if($alrTaken){
            return response()->json([
                'message' => "akun dengan email {$alrTaken->email} sudah terdaftar"
            ]);
        }else{
            $sandiId = mt_rand(4, 10);
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
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $alrTaken = User::where('email', $request->email)->first();

        $sandiId = mt_rand(1000000000, 9999999999);

        if($alrTaken){
            return response()->json([
                'message' => "akun dengan email {$alrTaken->email} sudah terdaftar"
            ]);
        }else{

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
            $role = Role::findOrFail(2);
            
            $findUser->assignRole([$role]);
    
            return response()->json([
                'data' => $user, 'acces_token' => $token, 'sandi' => $sandi, 'token_type' => 'Bearer'
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

        if($alrTaken){
            return response()->json([
                'message' => "akun dengan email {$alrTaken->email} sudah terdaftar"
            ]);
        }else{

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

        if(!$user){
            return response()->json([
                'message' => 'user not found'], 404);
        }

        $user_pw = Sandi::where('id', $user->sandi_id)->first();

        if ($user && Hash::check($request->password, $user_pw->password)) 
        {
            $token = $user->createToken('user log in')->plainTextToken;
            
            return response()->json([
            'data' => $user, 
            'acces_token' => $token,
            'token_type' => 'Bearer']);

        }
        else{
            return response()->json([
                'message' => 'password salah'], 404);
        }

        
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function getPersonalInformation(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            return response()->json([
                'message' => 'success',
                'user'=> $user,
            ]);
        }else{
            return response()->json([
                'user'=>'user not found'
            ], 404);
        }
    }

    // public function getSandi(Request $request)
    // {
    //     $user = Auth::user();
    //     $sandiId = $request->sandiId;
    //     $sandi = Sandi::where('id', $sandiId)->first();

    //     if ($user) {
    //         return response()->json([
    //             'message' => 'success',
    //             'user'=> $user,
    //             'sandi' => $sandi
    //         ]);
    //     }else{
    //         return response()->json([
    //             'user'=>'user not found'
    //         ], 404);
    //     }
    // }
}
