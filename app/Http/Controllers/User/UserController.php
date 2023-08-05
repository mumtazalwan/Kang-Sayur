<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        return response()->json([
            'status' => 200,
            'message' => "user personal information",
            'data' => $user,
        ]);
    }

    public function adminProfile()
    {
        $user = Auth::user();

        return response()->json([
            'status' => 200,
            'message' => "admin personal information",
            'data' => $user,
        ]);
    }

    public function updateUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'photo' => 'file|image|mimes:png,jpg,jpeg|max:3048',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'tanggal_lahir' => 'required',
            'address' => 'required',
            'longitude' => 'required|between:-180,180',
            'latitude' => 'required|between:-90,90'
        ]);

        $user = Auth::user();

        if ($request->photo) {
            // store photo
            $timestamp = time();
            $photoName = $timestamp . $request->photo->getClientOriginalName();
            $path = '/user_profile/' . $photoName;
            Storage::disk('public')->put($path, file_get_contents($request->photo));

            if ($user->photo) {
                $photoPath = public_path($user->photo);
                if (\Illuminate\Support\Facades\File::exists($photoPath)) {
                    \Illuminate\Support\Facades\File::delete($photoPath);
                }
            }

            // create user
            User::where('users.id', $user->id)
                ->update([
                    'name' => request('name'),
                    'photo' => '/storage' . $path,
                    'email' => request('email'),
                    'phone_number' => request('phone_number'),
                    'sandi_id' => $user->sandi_id,
                    'jenis_kelamin' => $user->jenis_kelamin,
                    'tanggal_lahir' => request('tanggal_lahir'),
                    'address' => request('address'),
                    'latitude' => request('latitude'),
                    'longitude' => request('longitude')
                ]);
        } else {
            // create user
            User::where('users.id', $user->id)
                ->update([
                    'name' => request('name'),
                    'photo' => $user->photo,
                    'email' => request('email'),
                    'phone_number' => request('phone_number'),
                    'sandi_id' => $user->sandi_id,
                    'jenis_kelamin' => $user->jenis_kelamin,
                    'tanggal_lahir' => request('tanggal_lahir'),
                    'address' => request('address'),
                    'latitude' => request('latitude'),
                    'longitude' => request('longitude')
                ]);
        }

        return response()->json([
            'status' => '200',
            'message' => 'berhasil diupdate',
            'name' => $request->name
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
//    public function updateUser(Request $request)
//    {
//        $request->validate([
//            'name' => 'required|string',
//            'photo' => 'file|image|mimes:png,jpg,jpeg|max:3048',
//            'email' => 'required|email',
//            'phone_number' => 'required|numeric',
//            'tanggal_lahir' => 'required|date_format:U',
//            'address' => 'required',
//            'longitude' => 'required|between:-180,180',
//            'latitude' => 'required|between:-90,90'
//        ]);
//
//        $user = Auth::user();
//
//        if ($request->photo) {
//            // store photo
//            $timestamp = time();
//            $photoName = $timestamp . $request->photo->getClientOriginalName();
//            $path = '/user_profile/' . $photoName;
//            Storage::disk('public')->put($path, file_get_contents($request->photo));
//
//            // create user
//            User::where('users.id', $user->id)
//                ->update([
//                    'name' => $request->name,
//                    'photo' => '/storage' . $path,
//                    'email' => request('email'),
//                    'phone_number' => request('phone_number'),
//                    'sandi_id' => $user->sandi_id,
//                    'jenis_kelamin' => $user->jenis_kelamin,
//                    'tanggal_lahir' => request('tanggal_lahir'),
//                    'address' => request('address'),
//                    'latitude' => request('latitude'),
//                    'longitude' => request('longitude')
//                ]);
//        } else {
//            // create user
//            User::where('users.id', $user->id)
//                ->update([
//                    'name' => request('name'),
//                    'photo' => $user->photo,
//                    'email' => request('email'),
//                    'phone_number' => request('phone_number'),
//                    'sandi_id' => $user->sandi_id,
//                    'jenis_kelamin' => $user->jenis_kelamin,
//                    'tanggal_lahir' => request('tanggal_lahir'),
//                    'address' => request('address'),
//                    'latitude' => request('latitude'),
//                    'longitude' => request('longitude')
//                ]);
//        }
//
//        return response()->json([
//            'status' => '200',
//            'message' => 'berhasil diupdate',
//            'name' => $request->name
//        ]);
//
//    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
