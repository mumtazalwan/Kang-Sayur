<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || Hash::check($request->password, $user->password)) 
        {
            throw ValidationException::withMessages([
                'email' => ['The provider credentials are incorrect.'],
            ]);
        }

        return $user->createToken('user log in')->plainTextToken;
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function getPersonalInformation(Request $request)
    {
        // $user = User::where('id', $request->id)->first();
        $user = Auth::user();
        return response()->json(['user'=>$user]);

        // if ($user) {
        //     return response()->json(Auth::user()->id);
        // }else{
        //     return response()->json([
        //         'user'=>'user not found'
        //     ], 404);
        // }
    }
}
