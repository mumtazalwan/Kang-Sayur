<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function menunggu_diulas(Request $request)
    {
        $dataUser = Auth::user();

        $orderS = Order::where('user_id', $dataUser->id)
            ->join('produk', 'produk.id', '=', 'orders.product_id')
            ->join('variants', 'variants.id', '=', 'orders.variant_id')
            ->join('tokos', 'tokos.id', '=', 'orders.store_id')
            ->where('status_diulas', 'menunggu diulas')
            ->groupBy('orders.transaction_code', 'variants.id')
            ->orderBy('orders.created_at', 'ASC')
            ->select([
                'orders.id as order_id',
                'produk.id as produk_id',
                'produk.nama_produk as nama_produk',
                'variants.id as variant_id',
                'variants.variant_img as gambar_produk',
                'variants.variant as jenis_variant',
                'orders.store_id as toko_id',
                'tokos.nama_toko',
                'tokos.img_profile as gambar_toko',
                'tokos.alamat',
                'orders.transaction_code as transaction_code'
            ])
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'list produk yang belum direview',
            'data' => $orderS
        ]);
    }

    public function review(Request $request)
    {
        $idUser = Auth::user()->id;
        $request->validate([
            'rating' => 'required',
            'comment' => 'required',
            'img_product' => 'file|image|mimes:png,jpg,jpeg|max:3048',
            'product_id' => 'required',
            'toko_id' => 'required',
            'variant_id' => 'required',
            'transaction_code' => 'required'
        ]);

        if ($request->img_product) {
            // store photo
            $timestamp = time();
            $photoName = $timestamp . $request->img_product->getClientOriginalName();
            $path = '/user_profile/' . $photoName;
            Storage::disk('public')->put($path, file_get_contents($request->img_product));

            Review::create([
                'id_user' => $idUser,
                'rating' => request('rating'),
                'img_product' => '/storage' . $path,
                'comment' => request('comment'),
                'product_id' => request('product_id'),
                'toko_id' => request('toko_id'),
                'variant_id' => request('variant_id'),
                'transaction_code' => request('transaction_code'),
            ]);
        } else {
            Review::create([
                'id_user' => $idUser,
                'rating' => request('rating'),
                'comment' => request('comment'),
                'product_id' => request('product_id'),
                'toko_id' => request('toko_id'),
                'variant_id' => request('variant_id'),
                'transaction_code' => request('transaction_code'),
            ]);
        }

        DB::table('orders')
            ->join('variants', 'variants.id', '=', 'orders.variant_id')
            ->where('store_id', request('toko_id'))
            ->where('transaction_code', request('transaction_code'))
            ->where('variants.id', request('variant_id'))
            ->where('orders.user_id', $idUser)
            ->where('orders.status', 'Selesai')
            ->update(["status_diulas" => 'sudah diulas']);

        return response()->json([
            'status' => '200',
            'message' => 'berhasil memberikan penilaian',
        ]);
    }

    public function riwayat()
    {
        $dataUser = Auth::user();

        $data = Review::where('id_user', $dataUser->id)
            ->join('produk', 'produk.id', '=', 'reviews.product_id')
            ->join('variants', 'variants.id', '=', 'reviews.variant_id')
            ->join('tokos', 'tokos.id', '=', 'reviews.toko_id')
            ->select([
                'produk.id as produk_id',
                'produk.nama_produk as nama_produk',
                'variants.id as variant_id',
                'variants.variant_img as gambar',
                'variants.variant as jenis_variant',
                'tokos.nama_toko',
                'reviews.rating',
                'reviews.comment',
                'reviews.img_product as gambar_review',
                'reviews.created_at as tanggal_review'

            ])
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'history review',
            'data' => $data
        ]);
    }
}
