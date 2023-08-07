<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
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

//            Address::where('addresses.user_id', $user->id)
//                ->where('addresses.prioritas_alamat', 'Utama')
//                ->update([
//                    'user_id' => $user->id,
//                    'nama_penerima' => request('name'),
//                    'nomor_hp' => request('phone_number'),
//                    'alamat_lengkap' => request('address'),
//                    'latitude' => request('latitude'),
//                    'longitude' => request('longitude'),
//                    'prioritas_alamat' => "Utama",
//                ]);
        } else {

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

//            Address::where('addresses.user_id', $user->id)
//                ->where('addresses.prioritas_alamat', 'Utama')
//                ->update([
//                    'user_id' => $user->id,
//                    'nama_penerima' => request('name'),
//                    'nomor_hp' => request('phone_number'),
//                    'alamat_lengkap' => request('address'),
//                    'latitude' => request('latitude'),
//                    'longitude' => request('longitude'),
//                    'prioritas_alamat' => "Utama",
//                ]);
        }

        return response()->json([
            'status' => '200',
            'message' => 'berhasil diupdate',
            'name' => $request->name
        ]);
    }

    public function list_alamat()
    {
        $data = Address::where('user_id', Auth::user()->id)->get();

        return response()->json([
            'status' => '200',
            'message' => 'berhasil diupdate',
            'name' => $data
        ]);
    }

    public function tambah_alamat(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required',
            'phone_number' => 'required|numeric',
            'longitude' => 'required|between:-180,180',
            'latitude' => 'required|between:-90,90',
            'label_alamat' => 'required'
        ]);

        $label = request('label_alamat');
        $catatan = $request->catatan;

        if ($catatan) {
            Address::create([
                'user_id' => Auth::user()->id,
                'nama_penerima' => request('name'),
                'nomor_hp' => request('phone_number'),
                'alamat_lengkap' => request('address'),
                'longitude' => request('longitude'),
                'latitude' => request('latitude'),
                'label_alamat' => $label,
                'prioritas_alamat' => "Tambahan",
                'catatan' => $catatan
            ]);
        } else {
            Address::create([
                'user_id' => Auth::user()->id,
                'nama_penerima' => request('name'),
                'nomor_hp' => request('phone_number'),
                'alamat_lengkap' => request('address'),
                'longitude' => request('longitude'),
                'latitude' => request('latitude'),
                'label_alamat' => $label,
                'prioritas_alamat' => "Tambahan",
            ]);
        }

        return response()->json([
            'status' => '200',
            'message' => 'berhasil menambahkan alamat'
        ]);
    }
}
