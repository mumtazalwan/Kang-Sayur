<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InboxController extends Controller
{
    public function listInbox()
    {
        $sellerId = Auth::user();
        $tokoId = Toko::where('seller_id', $sellerId->id)->value('id');

        $dataPesanan = [];

        $transaction = Order::where('store_id', $tokoId)
            ->join('produk', 'produk.id', '=', 'orders.product_id')
            ->select(DB::raw('COUNT(orders.id) as jumlah_beli'))
            ->groupBy('transaction_code')
            ->first();

        $transaction2 = Order::where('store_id', $tokoId)
            ->join('produk', 'produk.id', '=', 'orders.product_id')
            ->select(
                DB::raw('CONCAT("Pesanan Baru No.", orders.transaction_code) as judul'),
                DB::raw('CONCAT(produk.nama_produk, " sejumlah ", COUNT(orders.id), " item") as body'),
                DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m-%d, %H:%i:%s") as tanggal'))
            ->groupBy('transaction_code')
            ->get();

        if ($transaction->jumlah_beli < 2) {
            $dataPesanan = $transaction2;
        } else {
            $dataPesanan = Order::where('store_id', $tokoId)
                ->join('produk', 'produk.id', '=', 'orders.product_id')
                ->select(
                    DB::raw('CONCAT("Pesanan Baru No.", orders.transaction_code) as judul'),
                    DB::raw('CONCAT(produk.nama_produk, " dan ", COUNT(orders.id)-1, " lainnya") as body'),
                    DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m-%d, %H:%i:%s") as tanggal'))
                ->groupBy('transaction_code')
                ->get();
        }

        $verification = DB::table('produk')
            ->select(
                DB::raw('CONCAT("Halo Seller") as judul'),
                DB::raw('CONCAT("Produk ", produk.nama_produk, " telah diverifikasi, silahkan cek produk anda kembali") as body'),
                DB::raw('DATE_FORMAT(statuses.updated_at, "%Y-%m-%d, %H:%i:%s") as tanggal')
            )
            ->where('produk.toko_id', $tokoId)
            ->join('statuses', 'statuses.produk_id', '=', 'produk.id')
            ->join('variants', 'variants.product_id', '=', 'produk.id')
            ->groupBy('produk.id')
            ->where('statuses.status', 'Accepted')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'list inbox',
            'data' => [
                'inbox_pesanan' => $dataPesanan,
                'inbox_verifikasi' => $verification
            ]
        ]);
    }
}
