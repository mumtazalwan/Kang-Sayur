<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InboxController extends Controller
{
    public function listPesanan()
    {
        $sellerId = Auth::user();
        $tokoId = Toko::where('seller_id', $sellerId->id)->value('id');

        $transaction = Order::where('store_id', $tokoId)
            ->join('produk', 'produk.id', '=', 'orders.product_id')
            ->select('orders.transaction_code as nomor_pesanan', DB::raw('COUNT(orders.id) - 1 as jumlah_pesanan'), 'produk.nama_produk')
            ->groupBy('transaction_code')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'list pesanan',
            'data' => $transaction
        ]);
    }
}
