<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function driverInfo()
    {
        $user = Auth::user();

        $driver = User::join('kendaraans', 'kendaraans.driver_id', '=', 'users.id')
            ->where('kendaraans.driver_id', $user->id)
            ->select('users.id as driver_id','users.photo','users.name', 'users.phone_number', 'kendaraans.jenis_kendaraan', 'kendaraans.nomor_polisi')
            ->first();

        return response()->json([
            'status' => 200,
            'message' => "user personal information",
            'data' => $driver,
        ]);
    }
}
