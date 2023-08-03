<?php

namespace App\Http\Controllers;

use App\Events\DeliveryLocationUpdate;
use App\Events\OrderTracking;
use App\Models\Kendaraan;
use App\Models\Order;
use App\Models\Toko;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function delivered(Request $request)
    {
        $transactions = Transaction::join('orders', 'orders.transaction_code', 'transactions.transaction_code')
            ->join('tokos', 'tokos.id', '=', 'orders.store_id')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('kendaraans', 'kendaraans.toko_id', '=', 'tokos.id')
            ->where('transactions.status', 'Sudah dibayar')
            ->groupBy('transactions.transaction_code', 'orders.store_id')
            ->orderBy('transactions.created_at', "DESC")
            ->select(
                'orders.*',
                'tokos.*',
                'users.name as nama_user',
                'users.id as user_id',
                'users.address as alamat_user',
                'users.photo as user_profile',
                'users.phone_number',
                'users.latitude as user_latitude',
                'users.longitude as user_longitude',
                DB::raw("6371 * acos(cos(radians(users.latitude))
            * cos(radians(tokos.latitude))
            * cos(radians(tokos.longitude) - radians(users.longitude))
            + sin(radians(users.latitude))
            * sin(radians(tokos.latitude))) * 3000 as ongkir")
            )
            ->get();

        $data = [];

        foreach ($transactions as $transaction) {
            $relatedOrders = $transaction->statusSelesai;

            if ($relatedOrders->isNotEmpty()) {
                $data[] = [
                    'user_profile' => $transaction->user_profile,
                    'nama_pemesan' => $transaction->nama_user,
                    'nomor_telfon' => $transaction->phone_number,
                    'alamat' => $transaction->alamat_user,
                    'user_lat' => $transaction->user_latitude,
                    'user_long' => $transaction->user_longitude,
                    'alamat_toko' => $transaction->alamat,
                    'toko_lat' => $transaction->latitude,
                    'toko_long' => $transaction->longitude,
                    'user_id' => $transaction->user_id,
                    'dipesan' => $transaction->created_at->format('d, M Y'),
                    'barang_pesanan' => $relatedOrders,
                    'tagihan' => [
                        'total_harga' => $relatedOrders->sum('harga_variant'),
                        'ongkos_kirim' => $transaction->ongkir,
                    ],
                    'total' => $relatedOrders->sum('harga_variant') + $transaction->ongkir
                ];
            }
        }

        return response()->json([
            'status' => '200',
            'message' => 'List barang yang sudah diantar',
            'data' => $data
        ]);
    }
}
