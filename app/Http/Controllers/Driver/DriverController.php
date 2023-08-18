<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Order;
use App\Models\Sandi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function driverInfo()
    {
        $user = Auth::user();

        $driver = User::join('kendaraans', 'kendaraans.driver_id', '=', 'users.id')
            ->where('kendaraans.driver_id', $user->id)
            ->select('users.id as driver_id', 'users.photo', 'users.name', 'users.phone_number', 'kendaraans.jenis_kendaraan', 'kendaraans.nomor_polisi')
            ->first();

        return response()->json([
            'status' => 200,
            'message' => "user personal information",
            'data' => $driver,
        ]);
    }

    public function analisa()
    {
        $user = Auth::user();

        $jumlah_menagntar = Order::where('delivered_by', $user->id)->groupBy('transaction_code')->get();

        $total_jarak = Order::where('delivered_by', $user->id)
            ->join('addresses', 'addresses.id', '=', 'orders.alamat_id')
            ->join('tokos', 'tokos.id', '=', 'orders.store_id')
            ->groupBy('transaction_code')
            ->sum(DB::raw("6371 * acos(cos(radians(addresses.latitude))
            * cos(radians(tokos.latitude))
            * cos(radians(tokos.longitude) - radians(addresses.longitude))
            + sin(radians(addresses.latitude))
            * sin(radians(tokos.latitude)))"));

        if (!$jumlah_menagntar) {
            $jumlah_menagntar = 0;
        }

        if (!$total_jarak) {
            $total_jarak = 0;
        }

        return response()->json([
            'status' => '200',
            'message' => 'Analisa',
            'data' => [
                'jumlah_mengatar' => count($jumlah_menagntar),
                'total_jarak' => $total_jarak
            ]
        ]);
    }

    public function deleteDriver(Request $request)
    {
        $driverId = $request->driverId;

        $user = User::where('id', $driverId)->first();
        $user->delete();

        $kendaraan = Kendaraan::where('driver_id', $driverId)->first();
        $kendaraan->delete();

        return response()->json([
            'status' => '200',
            'message' => 'Driver berhasil di delete'
        ]);

    }

    public function updatePassword(Request $request)
    {
        $driverId = $request->driverId;
        $newPassword = $request->newPassword;
        $user = Auth::user();

        Sandi::where('id', $driverId)
            ->update([
                'password' => Hash::make($newPassword)
            ]);

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil ubah paswword'
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'driver_id' => 'required',
            'name' => 'required|string',
            'photo' => 'file|image|mimes:png,jpg,jpeg|max:3048',
            'phone_number' => 'required|numeric|digits:11',
            'nama_kendaraan' => 'required',
            'nomor_polisi' => 'required',
        ]);

        $user = User::where('id', request('driver_id'))->first();

        if ($request->photo) {

            // store photo
            $timestamp = time();
            $photoName = $timestamp . $request->photo->getClientOriginalName();
            $path = '/user_profile/' . $photoName;
            Storage::disk('public')->put($path, file_get_contents($request->photo));

            User::where('id', $user->id)
                ->update([
                    'name' => request('name'),
                    'photo' => '/storage' . $path,
                    'phone_number' => request('phone_number')
                ]);
        } else {
            User::where('id', $user->id)
                ->update([
                    'name' => request('name'),
                    'photo' => $user->photo,
                    'phone_number' => request('phone_number')
                ]);
        }

        Kendaraan::where('driver_id', $user->id)
            ->update([
                'nomor_polisi' => request('nomor_polisi'),
                'nama_kendaraan' => request('nama_kendaraan'),
            ]);

        return response()->json([
            'status' => 200,
            'message' => 'Data driver berhasil diupdate'
        ]);
    }
}
