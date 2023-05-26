<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Cart;
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
    public function order()
    {
        $dataUser = Auth::user();
        $data = Cart::where('status', 'true')
            ->where('user_id', $dataUser->id)
            ->join('produk', 'produk.id', '=', 'produk_id')
            ->join('tokos', 'tokos.id', '=', 'carts.toko_id')
            ->select('tokos.img_profile', 'tokos.nama_toko', 'produk.id', 'produk.img_id', 'produk.nama_produk', 'produk.harga_produk')
            ->get();

        $subTotal = DB::table('carts')
            ->where('user_id', $dataUser->id)
            ->where('status', 'true')
            ->select(DB::raw('SUM(harga_produk) as subtotal'))
            ->join('produk', 'produk.id', '=', 'produk_id')
            ->first()->subtotal;

        $distance = DB::table('carts')
            ->join('tokos', 'tokos.id', '=', 'toko_id')
            ->select(DB::raw("6371 * acos(cos(radians(" . $dataUser->latitude . ")) 
        * cos(radians(tokos.latitude)) 
        * cos(radians(tokos.longitude) - radians(" . $dataUser->longitude . ")) 
        + sin(radians(" . $dataUser->latitude . ")) 
        * sin(radians(tokos.latitude))) as distance"))
            ->first()->distance;

        $ongkos = $distance * 3000;

        if ($subTotal <= 100000) {
            $biayaLayanan = 2500;
        } elseif ($subTotal <= 500000) {
            $biayaLayanan = 3000;
        } elseif ($subTotal <= 1000000) {
            $biayaLayanan = 4000;
        } elseif ($subTotal >= 1000000) {
            $biayaLayanan = 5000;
        }


        $total = $subTotal + $ongkos + $biayaLayanan;

        return response()->json([
            'status' => '200',
            'message' => 'Data Pesanan',
            'title' => 'Pesanan',
            'nama_user' => $dataUser->name,
            'alamat' => $dataUser->address,
            'data' => $data,
            'subtotal' => $subTotal,
            'ringkasan_pembayaran' => [
                'subtotal' => $subTotal,
                'ongkos_kirim' => $ongkos,
                'biaya_layanan' => $biayaLayanan
            ],
            'total' => $total
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
