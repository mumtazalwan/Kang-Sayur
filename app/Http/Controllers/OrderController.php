<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Cart;
use App\Models\Toko;
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

        $data = Toko::with('getProductCart')->get();

        // $total_barang = Toko::with('getProductCart')->select(DB::raw('sum(' . $data->subtotal . ')'));

        return response()->json([
            'status' => '200',
            'message' => 'Data Pesanan',
            'title' => 'Pesanan',
            'nama_user' => $dataUser->name,
            'alamat' => $dataUser->address,
            'data' => $data,
            'ringkasan_pembayaran' => [
                'total_barang' => 100000,
                'ongkos_kirim' => 20000,
                'biaya_layanan' => 2500
            ],
            'total_keseluruhan' => 10000
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
