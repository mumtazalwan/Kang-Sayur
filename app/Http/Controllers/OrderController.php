<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Toko;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /*User*/

    // pesanan user yang belum di konfirmasi
    public function pesananUser()
    {
        $data = Transaction::with('statusOrderUser')
            ->whereHas('statusOrderUser')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'List pesanan user',
            'data' => $data
        ]);
    }

    // pesanan yang sedang disiapkan seller
    public function disiapkanSeller()
    {
        $data = Transaction::with('statusPreparedUser')
            ->whereHas('statusPreparedUser')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'List barang yang sedang disiapkan',
            'data' => $data
        ]);
    }

    // status saat pesanan sudah diantar driver
    public function sedangDiantar()
    {
        $data = Transaction::with('statusDeliveredUser')
            ->whereHas('statusDeliveredUser')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'List barang yang sedang diantar driver',
            'data' => $data
        ]);
    }

    // setatus saat pesanan sudah sampai
    public function barangSampai()
    {
        $data = Transaction::with('statusDoneUser')
            ->whereHas('statusDoneUser')
            ->groupBy('transactions.transaction_code')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'List pesanan selesai',
            'data' => $data
        ]);
    }

    public function detailStatus(Request $request)
    {
        $transactionCode = $request->transactionCode;
        $userId = Auth::user()->id;

        $data = DB::table('orders')
            ->where('orders.transaction_code', $transactionCode)
            ->where('orders.user_id', $userId)
            ->join('variants', 'variants.id', '=', 'orders.variant_id')
            ->groupBy('variants.id')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'detail status order',
            'data' => $data
        ]);
    }

    /*Seller*/

    // status order yang perlu dikonfirmasi oleh seller
    public function pesanan()
    {
        $data = Transaction::with('statusOrder')
            ->whereHas('statusOrder')
            ->where('transactions.status', 'Sudah dibayar')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'List pesanan',
            'data' => $data
        ]);
    }

    // update status oleh seller menjadi disiapkan
    public function updateStatusPrepared(Request $request)
    {
        $dataUser = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $dataUser->id)->value('id');
        $transactionCode = $request->transactionCode;

        $orderS = Order::where('store_id', $tokoId)->where('transaction_code', $transactionCode)->first()->status;
        $order = Order::where('store_id', $tokoId)->where('transaction_code', $transactionCode)->get();

        if ($orderS == "Menunggu konfirmasi") {
            $order->toQuery()->update(array("status" => 'Sedang disiapkan'));

            return response()->json([
                'message' => 'Status berhasil diubah'
            ]);
        } else {
            return response()->json([
                'message' => 'Tidak menemukan transaksi'
            ]);
        }
    }

    // status order yang perlu diubah oleh seller menjadi siap diantar
    public function disiapkan()
    {
        $data = Transaction::with('statusPrepared')
            ->whereHas('statusPrepared')
            ->where('transactions.status', 'Sudah dibayar')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'List barang yang harus disipkan',
            'data' => $data
        ]);
    }

    // update status oleh seller menjadi siap diantar
    public function updateStatusReadyToPicked(Request $request)
    {
        $dataUser = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $dataUser->id)->value('id');
        $transactionCode = $request->transactionCode;

        $orderS = Order::where('store_id', $tokoId)->where('transaction_code', $transactionCode)->first()->status;
        $order = Order::where('store_id', $tokoId)->where('transaction_code', $transactionCode)->get();

        if ($orderS == "Sedang disiapkan") {
            $order->toQuery()->update(array("status" => 'Menunggu driver'));

            return response()->json([
                'message' => 'Status berhasil diubah'
            ]);
        } else {
            return response()->json([
                'message' => 'Tidak menemukan transaksi'
            ]);
        }
    }

    // status order saat menunggu driver 
    public function menunggu_driver()
    {
        $data = Transaction::with('statusReadyDelivered')
            ->whereHas('statusReadyDelivered')
            ->where('transactions.status', 'Sudah dibayar')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'List barang yang siap diantar driver',
            'data' => $data
        ]);
    }

    // status saat pesanan sudah diantar driver
    public function diantar()
    {
        $data = Transaction::with('statusDelivered')
            ->whereHas('statusDelivered')
            ->where('transactions.status', 'Sudah dibayar')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'List barang yang sedang diantar driver',
            'data' => $data
        ]);
    }

    // setatus saat pesanan sudah sampai
    public function selesai()
    {
        $data = Transaction::with('statusDone')
            ->whereHas('statusDone')
            ->where('transactions.status', 'Sudah dibayar')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'List pesanan selesai',
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    // checkout produk
    public function store(Request $request)
    {
        $checkout = $request->checkout;
        $dataUser = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $dataUser->id)->first();
        $code = fake()->unique()->numberBetween(100, 999);

        foreach ($checkout as $key) {
            Order::create([
                'transaction_code' => $code,
                'product_id' => $key['product_id'],
                'variant_id' => $key['variant_id'],
                'store_id' => $key['store_id'],
                'user_id' => $dataUser->id
            ]);
        }

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $grossAmount = DB::table('orders')
            ->join('variants', 'variants.id', 'orders.variant_id')
            ->where('transaction_code', $code)
            ->select(DB::raw('sum(variants.harga_variant) as gross_amount'))
            ->value('gross_amount');

        $qty = DB::table('orders')
            ->where('transaction_code', $code)
            ->select(DB::raw('count(orders.id) as qty'))
            ->value('qty');

        $params = array(
            'transaction_details' => array(
                'order_id' => $code,
                'gross_amount' => $grossAmount,
            ),
            'customer_details' => array(
                'first_name' => $dataUser->name,
                'last_name' => '',
                'address' => $dataUser->address,
                'phone' => $dataUser->phone_number,
                'qty' => $qty,
                'total_price' => $grossAmount
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        Transaction::create([
            'transaction_code' => $code,
            'user_id' => $dataUser->id,
            'payment_method' => 'BRIVA',
            'notes' => 'Nanasnya dikupa kulitnya, tapi jangan dimakan',
            'transaction_token' => $snapToken,
            'client_key' => config('midtrans.client_key')
        ]);

        return response()->json([
            'status' => 'succes',
            'data'  => [
                'snap_token' => $snapToken,
                'clinet_key' => config('midtrans.client_key')
            ]
        ]);
    }

    //callback midtrans
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' or $request->transaction_status == 'settlement') {

                $dataOrder = Order::where('transaction_code', $request->order_id)->get();
                $dataTransaction = Transaction::where('transaction_code', $request->order_id)->get();

                $dataTransaction->toQuery()->update(array("status" => 'Sudah dibayar'));
                $dataOrder->toQuery()->update(array("status" => 'Menunggu konfirmasi'));
            }
        }
    }
}
