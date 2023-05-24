<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    public function listCart()
    {
        $user = Auth::user();

        $list = Cart::where('user_id', $user->id)
            ->join('tokos', 'tokos.id', '=', 'toko_id')
            ->groupBy('produk.id', 'tokos.img_profile', 'tokos.nama_toko', 'tokos.alamat', 'produk.img_id', 'produk.nama_produk', 'produk.harga_produk', 'produk.varian_id')
            ->join('produk', 'produk.id', '=', 'produk_id')
            ->select('produk.id', 'tokos.img_profile', 'tokos.nama_toko', 'tokos.alamat', 'produk.img_id', 'produk.nama_produk', 'produk.harga_produk', 'produk.varian_id', DB::raw('COUNT(produk.id) as inCart'))
            ->get();

        return response()->json([
            'status' => 200,
            'message' => 'List cart',
            'data' => $list
        ]);
    }

    public function addToChart(Request $request)
    {
        $produkId = $request->produkId;
        $tokoId = $request->tokoId;
        $user = Auth::user();

        Cart::create([
            'user_id' => $user->id,
            'produk_id' => $produkId,
            'toko_id' => $tokoId
        ]);

        return response()->json([
            'message' => 'barang sudah dimasukan ke keranjang'
        ]);
    }

    public function custom(Request $request)
    {
        $customInpt = $request->customInpt;
        $user = Auth::user();
        $produkId = $request->produkId;
        $tokoId = $request->tokoId;

        $currentProduct = Cart::where('user_id', $user->id)
            ->join('tokos', 'tokos.id', '=', 'toko_id')
            ->groupBy('produk.id')
            ->join('produk', 'produk.id', '=', 'produk_id')
            ->select(DB::raw('COUNT(produk.id) as inCart'))
            ->first();

        if ($customInpt > $currentProduct->inCart) {

            for ($i = $currentProduct->inCart; $i < $customInpt; $i++) {
                Cart::create([
                    'user_id' => $user->id,
                    'produk_id' => $produkId,
                    'toko_id' => $tokoId
                ]);
            }

            return response()->json([
                'message' => 'jumlah barang berhasil ditabah'
            ]);
        } else if ($customInpt < $currentProduct->inCart) {

            for ($i = $currentProduct->inCart; $i > $customInpt; $i--) {
                $produk = Cart::where('user_id', $user->id)->where('produk_id', $produkId)->first();
                $produk->delete();
            }

            return response()->json([
                'message' => 'jumlah barang berhasil dikurang'
            ]);
        }
    }

    public function minus(Request $request)
    {
        $produkId = $request->produkId;
        $user = Auth::user();

        $produk = Cart::where('user_id', $user->id)->where('produk_id', $produkId)->first();
        $produk->delete();

        return response()->json([
            'message' => 'jumlah barang berhasil dikurang'
        ]);
    }

    public function deleteAll(Request $request)
    {
        $produkId = $request->produkId;
        $user = Auth::user();

        $produk = Cart::where('user_id', $user->id)->where('produk_id', $produkId);
        $produk->delete();

        return response()->json([
            'message' => 'barang sudah dihapus dari keranjang'
        ]);
    }
}
