<?php

namespace App\Http\Controllers;

use App\Models\StoreAdvertising;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StoreAdvertisingController extends Controller
{
    public function list()
    {
        $mytime = Carbon::now()->addDay(1)->format('Y-m-d');
        $data = StoreAdvertising::where('expire_through', '>', $mytime)
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'display iklan toko hari ini',
            'data' => $data,
        ]);
    }

    public function add(Request $request)
    {
        $dataUser = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $dataUser->id)->value('id');
        // $check = ProductAdvertising::where('toko_id', 200)->get();
        $check = null;
        $count = StoreAdvertising::all()->count();

        $request->validate([
            'img_pamflet' => 'file|image|mimes:png,jpg,jpeg|max:3048',
        ]);

        if (is_null($check)) {
            if ($count >= 5) {
                if ($request->img_pamflet) {
                    $addDay = $count / 5;
                    $now = Carbon::now()->addDay($addDay + 1);

                    // store photo
                    $timestamp = time();
                    $photoName = $timestamp . $request->img_pamflet->getClientOriginalName();
                    $path = '/user_profile/' . $photoName;
                    Storage::disk('public')->put($path, file_get_contents($request->img_pamflet));


                    $data = StoreAdvertising::create([
                        'toko_id' => $tokoId,
                        'img_pamflet' => '/storage' . $path,
                        'expire_through' => $now->format('Y-m-d H:i:s'),
                    ]);

                    return response()->json([
                        'message' => 'data berhasil ditambahkan',
                        'data' => $data,
                    ]);
                } else {
                    return response()->json([
                        'messaga' => 'lengkapi data terlebih dahulu'
                    ]);
                }
            } else {
                if ($request->img_pamflet) {
                    $now = Carbon::now()->addDay(1);

                    // store photo
                    $timestamp = time();
                    $photoName = $timestamp . $request->img_pamflet->getClientOriginalName();
                    $path = '/user_profile/' . $photoName;
                    Storage::disk('public')->put($path, file_get_contents($request->img_pamflet));


                    $data = StoreAdvertising::create([
                        'toko_id' => $tokoId,
                        'img_pamflet' => '/storage' . $path,
                        'expire_through' => $now->format('Y-m-d H:i:s'),
                    ]);

                    return response()->json([
                        'message' => 'data berhasil ditambahkan',
                        'data' => $data
                    ]);
                } else {
                    return response()->json([
                        'messaga' => 'lengkapi data terlebih dahulu'
                    ]);
                }
            }
        } else {
            return response()->json(['message' => 'sudah ada produk kamu yang terdaftar dalam antrean iklan']);
        }
    }
}