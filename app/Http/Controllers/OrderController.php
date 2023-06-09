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

    public function confirm()
    {
        $data = Transaction::with('getProductOrder')->get();

        return response()->json([
            'status' => '200',
            'message' => 'Data Pesanan',
            'title' => 'Konfirmasi',
            'data' => $data,
            // 'ringkasan_pembayaran' => [
            //     'total_barang' => 100000,
            //     'ongkos_kirim' => 20000,
            //     'biaya_layanan' => 2500
            // ],
            // 'total_keseluruhan' => 10000
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    // mendaftarkan produk
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

        $total_transaction = DB::table('orders')
            ->where('transaction_code', $code)
            ->where('store_id', $tokoId)
            ->select(DB::raw('sum(produk.harga_produk) as subtotal'));

        Transaction::create([
            'transaction_code' => $code,
            'user_id' => $dataUser->id,
            'payment_method' => 'BRIVA',
            'notes' => 'Nanasnya dikupa kulitnya, tapi jangan dimakan'
        ]);

        return response()->json([
            'status' => 'succes',
        ]);
    }
}
