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
            ->whereHas('statusOrder')
            ->where('transactions.status', 'Sudah dibayar')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'List pesanan',
            'data' => $data
        ]);
    }

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
