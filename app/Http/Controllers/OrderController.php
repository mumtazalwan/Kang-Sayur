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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */

    // status order yang perlu dikonfirmasi

    public function pesanan()
    {
        $data = Transaction::with('statusOrder')
            ->join('orders', 'orders.transaction_code', '=', 'transactions.transaction_code')
            ->join('tokos', 'tokos.id', '=', 'orders.store_id')
            ->where('transactions.status', 'Belum dibayar')->get();

        return response()->json([
            'status' => '200',
            'message' => 'List pesanan',
            'data' => $data
        ]);
    }

    public function disiapkan()
    {
        $data = Transaction::with('statusPrepared')->where('transactions.status', 'Sudah dibayar')->get();

        return response()->json([
            'status' => '200',
            'message' => 'List barang yang harus disipkan',
            'data' => $data
        ]);
    }

    public function updateStatusPrepared(Request $request)
    {
        $orderId = $request->orderId;

        $dataOrder = Order::findOrfail($orderId);

        $dataOrder->status = 'Menunggu driver';
        $dataOrder->save();
    }

    public function menunggu_driver()
    {
        $data = Transaction::with('statusReadyDelivered')->where('transactions.status', 'Sudah dibayar')->get();

        return response()->json([
            'status' => '200',
            'message' => 'List barang yang siap diantar driver',
            'data' => $data
        ]);
    }

    public function diantar()
    {
        $data = Transaction::with('statusDelivered')->where('transactions.status', 'Sudah dibayar')->get();

        return response()->json([
            'status' => '200',
            'message' => 'List barang yang sedang diantar driver',
            'data' => $data
        ]);
    }

    public function selesai()
    {
        $data = Transaction::with('statusDone')->where('transactions.status', 'Sudah dibayar')->get();

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
        $code = fake()->unique()->numberBetween(1, 9999999999999);

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

        $params = array(
            'transaction_details' => array(
                'order_id' => $dataUser->id,
                'gross_amount' => 20000,
            ),
            'customer_details' => array(
                'first_name' => $dataUser->name,
                'last_name' => '',
                'address' => $dataUser->address,
                'phone' => $dataUser->phone_number,
                'qty' => 2,
                'total_price' => 20000
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
        ]);
    }


    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' or $request->transaction_status == 'settlement') {
                $dataOrder = Order::findOrfail($request->order_id);
                $dataTransaction = Transaction::findOrFail($dataOrder->transaction_code);

                $dataTransaction->status = 'Sudah dibayar';
                $dataTransaction->save();

                $dataOrder->status = 'Menunggu konfirmasi';
                $dataOrder->save();
            }
        }
    }
}
