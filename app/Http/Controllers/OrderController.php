<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Inbox;
use App\Models\Order;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Toko;
use App\Models\Transaction;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    /*User*/

    // pesanan user yang belum di konfirmasi
    public function pesananUser()
    {
        $transactions = Transaction::join('orders', 'orders.transaction_code', 'transactions.transaction_code')
            ->join('tokos', 'tokos.id', '=', 'orders.store_id')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->where('transactions.status', 'Sudah dibayar')
            ->join('addresses', 'addresses.id', '=', 'orders.alamat_id')
            ->groupBy('transactions.transaction_code', 'orders.store_id')
            ->orderBy('transactions.created_at', "DESC")
            ->select(
                'addresses.*',
                'orders.*',
                'tokos.*',
                'users.name as nama_user',
                'users.address as alamat_user',
                'users.phone_number',
                'users.latitude as user_latitude',
                'users.longitude as user_longitude',
                'orders.created_at as order_date',
                DB::raw("6371 * acos(cos(radians(addresses.latitude))
            * cos(radians(tokos.latitude))
            * cos(radians(tokos.longitude) - radians(addresses.longitude))
            + sin(radians(addresses.latitude))
            * sin(radians(tokos.latitude))) * 3000 as ongkir")
            )
            ->get();

        $data = [];

        foreach ($transactions as $transaction) {
            $relatedOrders = $transaction->statusOrderUser;

            if ($relatedOrders->isNotEmpty()) {
                $data[] = [
                    'icon' => 'status/toko',
                    'nama_toko' => $transaction->nama_toko,
                    'profil_toko' => $transaction->img_profile,
                    'alamat_toko' => $transaction->alamat,
                    'tanggal' => $transaction->order_date,
                    'kode_transaksi' => $transaction->transaction_code,
                    'toko_id' => $transaction->store_id,
                    'alamat pengiriman' => [
                        'nama_pemesan' => $transaction->nama_penerima,
                        'nomor_telfon' => $transaction->nomor_hp,
                        'alamat' => $transaction->alamat_lengkap
                    ],
                    'barang_pesanan' => $relatedOrders,
                    'tagihan' => [
                        'total_harga' => $relatedOrders->sum('pembelian'),
                        'ongkos_kirim' => $transaction->ongkir,
                    ],
                    'total' => $relatedOrders->sum('pembelian') + $transaction->ongkir,
                ];
            }
        }

        return response()->json([
            'status' => '200',
            'message' => 'List pesanan',
            'data' => $data
        ]);
    }

    // pesanan yang sedang disiapkan seller
    public function disiapkanSeller()
    {
        $transactions = Transaction::join('orders', 'orders.transaction_code', 'transactions.transaction_code')
            ->join('tokos', 'tokos.id', '=', 'orders.store_id')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->where('transactions.status', 'Sudah dibayar')
            ->join('addresses', 'addresses.id', '=', 'orders.alamat_id')
            ->groupBy('transactions.transaction_code', 'orders.store_id')
            ->orderBy('transactions.created_at', "DESC")
            ->select(
                'addresses.*',
                'orders.*',
                'tokos.*',
                'users.name as nama_user',
                'users.address as alamat_user',
                'users.phone_number',
                'users.latitude as user_latitude',
                'users.longitude as user_longitude',
                'orders.created_at as order_date',
                DB::raw("6371 * acos(cos(radians(addresses.latitude))
            * cos(radians(tokos.latitude))
            * cos(radians(tokos.longitude) - radians(addresses.longitude))
            + sin(radians(addresses.latitude))
            * sin(radians(tokos.latitude))) * 3000 as ongkir")
            )
            ->get();

        $data = [];

        foreach ($transactions as $transaction) {
            $relatedOrders = $transaction->statusPreparedUser;

            if ($relatedOrders->isNotEmpty()) {
                $data[] = [
                    'icon' => 'status/toko',
                    'nama_toko' => $transaction->nama_toko,
                    'profil_toko' => $transaction->img_profile,
                    'alamat_toko' => $transaction->alamat,
                    'tanggal' => $transaction->order_date,
                    'kode_transaksi' => $transaction->transaction_code,
                    'toko_id' => $transaction->store_id,
                    'alamat pengiriman' => [
                        'nama_pemesan' => $transaction->nama_penerima,
                        'nomor_telfon' => $transaction->nomor_hp,
                        'alamat' => $transaction->alamat_lengkap
                    ],
                    'barang_pesanan' => $relatedOrders,
                    'tagihan' => [
                        'total_harga' => $relatedOrders->sum('pembelian'),
                        'ongkos_kirim' => $transaction->ongkir,
                    ],
                    'total' => $relatedOrders->sum('pembelian') + $transaction->ongkir,
                ];
            }
        }

        return response()->json([
            'status' => '200',
            'message' => 'List barang yang sedang disiapkan',
            'data' => $data
        ]);
    }

    // status saat pesanan sudah diantar driver
    public function sedangDiantar()
    {
        $transactions = Transaction::join('orders', 'orders.transaction_code', 'transactions.transaction_code')
            ->join('tokos', 'tokos.id', '=', 'orders.store_id')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->where('transactions.status', 'Sudah dibayar')
            ->join('addresses', 'addresses.id', '=', 'orders.alamat_id')
            ->groupBy('transactions.transaction_code', 'orders.store_id')
            ->orderBy('transactions.created_at', "DESC")
            ->select(
                'addresses.nama_penerima',
                'addresses.nomor_hp',
                'addresses.alamat_lengkap',
                'addresses.latitude as address_lat',
                'addresses.longitude as address_long',
                'orders.*',
                'tokos.*',
                'users.name as nama_user',
                'users.address as alamat_user',
                'users.phone_number',
                'users.latitude as user_latitude',
                'users.longitude as user_longitude',
                'orders.created_at as order_date',
                DB::raw("6371 * acos(cos(radians(addresses.latitude))
            * cos(radians(tokos.latitude))
            * cos(radians(tokos.longitude) - radians(addresses.longitude))
            + sin(radians(addresses.latitude))
            * sin(radians(tokos.latitude))) * 3000 as ongkir")
            )
            ->get();

        $data = [];

        foreach ($transactions as $transaction) {
            $relatedOrders = $transaction->statusDeliveredUser;

            if ($relatedOrders->isNotEmpty()) {
                $data[] = [
                    'icon' => 'status/toko',
                    'nama_toko' => $transaction->nama_toko,
                    'profil_toko' => $transaction->img_profile,
                    'alamat_toko' => $transaction->alamat,
                    'tanggal' => $transaction->order_date,
                    'kode_transaksi' => $transaction->transaction_code,
                    'toko_id' => $transaction->store_id,
                    'alamat pengiriman' => [
                        'nama_pemesan' => $transaction->nama_penerima,
                        'nomor_telfon' => $transaction->nomor_hp,
                        'user_lat' => $transaction->address_lat,
                        'user_long' => $transaction->address_long,
                        'alamat' => $transaction->alamat_lengkap
                    ],
                    'barang_pesanan' => $relatedOrders,
                    'tagihan' => [
                        'total_harga' => $relatedOrders->sum('pembelian'),
                        'ongkos_kirim' => $transaction->ongkir,
                    ],
                    'total' => $relatedOrders->sum('pembelian') + $transaction->ongkir,
                ];
            }
        }

        return response()->json([
            'status' => '200',
            'message' => 'List barang yang sedang diantar driver',
            'data' => $data
        ]);
    }

    // setatus saat pesanan sudah sampai
    public function barangSampai()
    {
        $transactions = Transaction::join('orders', 'orders.transaction_code', '=', 'transactions.transaction_code')
            ->join('tokos', 'tokos.id', '=', 'orders.store_id')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->where('transactions.status', 'Sudah dibayar')
            ->join('addresses', 'addresses.id', '=', 'orders.alamat_id')
            ->groupBy('transactions.transaction_code', 'orders.store_id')
            ->orderBy('transactions.created_at', "DESC")
            ->select(
                'addresses.*',
                'orders.*',
                'tokos.*',
                'users.name as nama_user',
                'users.address as alamat_user',
                'users.phone_number',
                'users.latitude as user_latitude',
                'users.longitude as user_longitude',
                'orders.created_at as order_date',
                DB::raw("6371 * acos(cos(radians(addresses.latitude))
            * cos(radians(tokos.latitude))
            * cos(radians(tokos.longitude) - radians(addresses.longitude))
            + sin(radians(addresses.latitude))
            * sin(radians(tokos.latitude))) * 3000 as ongkir")
            )
            ->get();

        $data = [];

        foreach ($transactions as $transaction) {
            $relatedOrders = $transaction->statusDoneUser;

            if ($relatedOrders->isNotEmpty()) {
                $data[] = [
                    'icon' => 'status/toko',
                    'nama_toko' => $transaction->nama_toko,
                    'profil_toko' => $transaction->img_profile,
                    'alamat_toko' => $transaction->alamat,
                    'tanggal' => $transaction->order_date,
                    'kode_transaksi' => $transaction->transaction_code,
                    'toko_id' => $transaction->store_id,
                    'alamat pengiriman' => [
                        'nama_pemesan' => $transaction->nama_penerima,
                        'nomor_telfon' => $transaction->nomor_hp,
                        'alamat' => $transaction->alamat_lengkap
                    ],
                    'barang_pesanan' => $relatedOrders,
                    'tagihan' => [
                        'total_harga' => $relatedOrders->sum('pembelian'),
                        'ongkos_kirim' => $transaction->ongkir,
                    ],
                    'total' => $relatedOrders->sum('pembelian') + $transaction->ongkir,
                ];
            }
        }

        return response()->json([
            'status' => '200',
            'message' => 'List pesanan selesai',
            'data' => $data,
        ]);
    }

    /*Seller*/

    // status order yang perlu dikonfirmasi oleh seller
    public function pesanan()
    {
        $data = Transaction::with('statusOrder')
            ->whereHas('statusOrder')
            ->where('transactions.status', 'Sudah dibayar')
            ->orderBy('transactions.created_at', "DESC")
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
            ->orderBy('transactions.created_at', "DESC")
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
            ->orderBy('transactions.created_at', "DESC")
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
            ->orderBy('transactions.created_at', "DESC")
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
            ->orderBy('transactions.created_at', "DESC")
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
        $code = fake()->unique()->numberBetween(1, 999);

        $total_keseluruhan = $request->total_keseluruhan;

        foreach ($checkout as $key) {
            Order::create([
                'transaction_code' => $code,
                'product_id' => $key['product_id'],
                'variant_id' => $key['variant_id'],
                'store_id' => $key['store_id'],
                'notes' => !isset($key['notes']) ? "" : $key['notes'],
                'alamat_id' => $key['alamat_id'],
                'user_id' => $dataUser->id,
            ]);
        }

        // Set your Merchant Server Key
        Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = false;
        // Set sanitization on (default)
        Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;

//        $grossAmount = DB::table('orders')
//            ->join('variants', 'variants.id', 'orders.variant_id')
//            ->join('carts', 'carts.variant_id', '=', 'variants.id')
//            ->where('carts.status', 'true')
//            ->where('carts.user_id', $dataUser->id)
//            ->where('transaction_code', $code)
//            ->select(DB::raw('sum(variants.harga_variant) as gross_amount'))
//            ->value('gross_amount');

        $qty = DB::table('orders')
            ->where('transaction_code', $code)
            ->select(DB::raw('count(orders.id) as qty'))
            ->value('qty');

        $params = array(
            'transaction_details' => array(
                'order_id' => $code,
                'gross_amount' => $total_keseluruhan,
            ),
            'customer_details' => array(
                'first_name' => $dataUser->name,
                'last_name' => '',
                'address' => $dataUser->address,
                'phone' => $dataUser->phone_number,
                'qty' => $qty,
                'total_price' => $total_keseluruhan
            ),
        );

        $snapToken = Snap::getSnapToken($params);

        Transaction::create([
            'transaction_code' => $code,
            'user_id' => $dataUser->id,
            'payment_method' => 'BRIVA',
            'notes' => 'Nanasnya dikupa kulitnya, tapi jangan dimakan',
            'transaction_token' => $snapToken,
            'client_key' => config('midtrans.client_key')
        ]);

        $deleteCart = Cart::where('status', 'true')
            ->where('user_id', Auth::user()->id)
            ->get();

        $deleteCart->toQuery()->delete();

        return response()->json([
            'status' => 'succes',
            'data' => [
                'snap_token' => $snapToken,
                'clinet_key' => config('midtrans.client_key')
            ]
        ]);
    }

    //callback midtrans
    public function  callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' or $request->transaction_status == 'settlement') {

                $dataOrder = Order::where('transaction_code', $request->order_id)->get();
                $dataTransaction = Transaction::where('transaction_code', $request->order_id)->get();

                $dataTransaction->toQuery()->update(array("status" => 'Sudah dibayar'));
                $dataOrder->toQuery()->update(array("status" => 'Menunggu konfirmasi'));

                foreach ($dataOrder as $orders) {

//                    $transactions = Order::where('transaction_code', $request->order_id)
//                        ->join('produk', 'produk.id', '=', 'orders.product_id')
//                        ->join('tokos', 'tokos.id', $orders->store_id)
//                        ->select('orders.transaction_code as nomor_pesanan',
//                            DB::raw('COUNT(orders.id) - 1 as jumlah_pesanan'),
//                            'produk.nama_produk',
//                            'tokos.nama_toko',
//                            'tokok.device_token')
//                        ->groupBy('transaction_code')
//                        ->get();

                    Inbox::create([
                        'user_id' => $orders->store_id,
                        'judul' => "Pesanan Baru, No. $orders->transaction_code",
                        'body' => "HI seller, ada pesanan baru lho.. segera di verifikasi ya"
                    ]);

                    $fcmservicekey = "AAAAyKjEhRs:APA91bEhFcJBjxY6U-I-eXoHFLVrdWE1WAVaI9ZhsGjFfpfdmRDdL1s8Mc7HLSptWJVB_i1gyluUaa22r0Q6mXxQ8gVRepRNgyoJjCnDG4Jdi6DgMgOo-CiX8017bV_pY2oVuTN0OVUi";
                    $headers = [
                        'Authorization: key=' . $fcmservicekey,
                        'Content-Type: application/json',
                    ];

                    $ch = curl_init();

                    $data = [
                        "registration_ids" => ['dlxfIk2eQ3CRm4bASIVFuu:APA91bGBkEQQkSBo4KfXFiCyAKcrP5QsgCkR3dD0oshs6fu6DjTKWmjJPtYMSSMdmhFcED1I1xaznMZO2BbYhMDUi23qcPrPmlkT6HItMN5hVGAzYUO4sWG12JtgKZ-ajkEFw9wLdScE'],
                        "notification" => [
                            "title" => "Pesanan Baru $orders->transaction_code",
                            "body" => "Ada pesanan nih. Ayo segara konfirmasi pesanan",
                            "content_available" => true,
                            "priority" => "high",
                        ],
                    ];
                    $dataString = json_encode($data);

                    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

                    $response = curl_exec($ch);

                    Log::info($response);

                    if ($response === false) {
                        Log::error(curl_error($ch));
                    }

                    curl_close($ch);

                }
            }
        }
    }

    // driver
    public function readyToPickedUp()
    {
        $transactions = Transaction::join('orders', 'orders.transaction_code', 'transactions.transaction_code')
            ->join('tokos', 'tokos.id', '=', 'orders.store_id')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('kendaraans', 'kendaraans.toko_id', '=', 'tokos.id')
            ->where('transactions.status', 'Sudah dibayar')
            ->join('addresses', 'addresses.id', '=', 'orders.alamat_id')
            ->groupBy('transactions.transaction_code', 'orders.store_id')
            ->orderBy('transactions.created_at', "DESC")
            ->select(
                'addresses.latitude as address_lat',
                'addresses.longitude as address_long',
                'orders.*',
                'tokos.*',
                'users.name as nama_user',
                'users.id as user_id',
                'users.address as alamat_user',
                'users.photo as user_profile',
                'users.phone_number',
                'users.latitude as user_latitude',
                'users.longitude as user_longitude',
                DB::raw("6371 * acos(cos(radians(addresses.latitude))
            * cos(radians(tokos.latitude))
            * cos(radians(tokos.longitude) - radians(addresses.longitude))
            + sin(radians(addresses.latitude))
            * sin(radians(tokos.latitude))) * 3000 as ongkir")
            )
            ->get();

        $data = [];

        foreach ($transactions as $transaction) {
            $relatedOrders = $transaction->statusSiapDiantar;

            if ($relatedOrders->isNotEmpty()) {
                $data[] = [
                    'user_profile' => $transaction->user_profile,
                    'nama_pemesan' => $transaction->nama_user,
                    'nomor_telfon' => $transaction->phone_number,
                    'alamat' => $transaction->alamat_user,
                    'user_lat' => $transaction->address_lat,
                    'user_long' => $transaction->address_long,
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
            'message' => 'List antrean order untuk diantar',
            'data' => $data
        ]);
    }
}
