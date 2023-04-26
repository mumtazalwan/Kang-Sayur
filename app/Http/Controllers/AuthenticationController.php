<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{

    public function register(Request $request)
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
            $user = User::create([
                'name' => request('name'),
                'email' => request('email'),
                'password' => Hash::make(request('password'))
            ]);
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'data' => $user, 'acces_token' => $token, 'token_type' => 'Bearer'
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

        if ($user && Hash::check($request->password, $user->password)) 
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
}
