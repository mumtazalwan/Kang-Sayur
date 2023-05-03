<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Access\Authorizable;

class UserPersonalInformationController extends Controller
{
    public function getPersonalInformation(Request $request)
    {
        $user = Auth::user();
        
        // if ($user->hasRole('user')) {
        //     return response()->json([
        //         'message' => 'success',
        //         'user'=> $user,
        //     ]);
        // }else{
        //     return response()->json([
        //         'user'=>'user not found'
        //     ], 404);
        // }

        return response()->json([
            'message' => 'success',
            'user'=> $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
}
