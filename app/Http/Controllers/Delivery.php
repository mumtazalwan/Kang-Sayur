<?php

namespace App\Http\Controllers;

use App\Events\DeliveryLocationUpdate;
use App\Events\OrderTracking;
use App\Models\Kendaraan;
use App\Models\Order;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Delivery extends Controller
{

    public function takeOrder(Request $request)
    {
        $request->validate([
            'transaction_code' => 'required',
            'store_id' => 'required',
            'user_id' => 'required'
        ]);

        $orderS = Order::where('store_id', request('store_id'))->where('transaction_code', request('transaction_code'))->first()->status;
        $order = Order::where('store_id', request('store_id'))->where('transaction_code', request('transaction_code'))->get();

        if ($orderS == "Menunggu driver") {
            $order->toQuery()->update(array("status" => 'Sedang diantar'));

            return response()->json([
                'message' => 'Status berhasil diubah'
            ]);
        } else {
            return response()->json([
                'message' => 'Tidak menemukan transaksi'
            ]);
        }
    }

    //ubah status menjadi sedang diantar + create new event
    public function updateLoc(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'transaction_code' => 'required'
        ]);

        event(new OrderTracking(request('transaction_code'), request('lat'), request('long')));

        return response()->json([
            'status' => '200',
            'message' => 'berhasil membuat new event',
        ]);
    }

    // ubah status menjadi selesai
    public function finishOrder(Request $request)
    {
        $request->validate([
            'transaction_code' => 'required',
            'store_id' => 'required',
            'user_id' => 'required'
        ]);

        $orderS = Order::where('store_id', request('store_id'))->where('transaction_code', request('transaction_code'))->first()->status;
        $order = Order::where('store_id', request('store_id'))->where('transaction_code', request('transaction_code'))->get();

        if ($orderS == "Sedang diantar") {
            $order->toQuery()->update(array("status" => 'Selesai'));

            return response()->json([
                'message' => 'Status berhasil diubah'
            ]);
        } else {
            return response()->json([
                'message' => 'Tidak menemukan transaksi'
            ]);
        }
    }
}
