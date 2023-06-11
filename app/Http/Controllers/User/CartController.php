<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    public function listCart()
    {
        $user = Auth::user();

        // $list = Cart::where('user_id', $user->id)
        //     ->join('tokos', 'tokos.id', '=', 'toko_id')
        //     ->groupBy('produk.id', 'tokos.img_profile', 'tokos.id', 'tokos.nama_toko', 'tokos.alamat', 'produk.img_id', 'produk.nama_produk', 'produk.harga_produk', 'produk.varian_id')
        //     ->join('produk', 'produk.id', '=', 'produk_id')
        //     ->select('produk.id', 'tokos.img_profile', 'tokos.id as toko_id', 'tokos.nama_toko', 'tokos.alamat', 'produk.img_id', 'produk.nama_produk', 'produk.harga_produk', 'produk.varian_id', DB::raw('COUNT(produk.id) as inCart'))
        //     ->get();

        $list = Toko::with('getProductCart')
            ->whereHas('getProductCart')
            ->select(['tokos.id', 'tokos.img_profile', 'tokos.nama_toko'])
            ->get();

        return response()->json([
            'status' => 200,
            'message' => 'List cart',
            'data' => $list
        ]);
    }

    public function addToChart(Request $request)
    {
        $user = Auth::user();

        $produkId = $request->produkId;
        $tokoId = $request->tokoId;
        $variantId = $request->variantId;

        Cart::create([
            'user_id' => $user->id,
            'toko_id' => $tokoId,
            'produk_id' => $produkId,
            'variant_id' => $variantId
        ]);

        return response()->json([
            'message' => 'barang sudah dimasukan ke keranjang'
        ]);
    }

    public function custom(Request $request)
    {
        $user = Auth::user();

        $customInpt = $request->customInpt;
        $produkId = $request->produkId;
        $tokoId = $request->tokoId;
        $variantId = $request->variantId;

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
                    'variant_id' => $variantId,
                    'toko_id' => $tokoId
                ]);
            }

            return response()->json([
                'message' => 'jumlah barang berhasil ditabah'
            ]);
        } else if ($customInpt < $currentProduct->inCart) {

            for ($i = $currentProduct->inCart; $i > $customInpt; $i--) {
                $produk = Cart::where('user_id', $user->id)->where('produk_id', $produkId)->where('variant_id', $variantId)->first();
                $produk->delete();
            }

            return response()->json([
                'message' => 'jumlah barang berhasil dikurang'
            ]);
        }
    }

    public function minus(Request $request)
    {
        $user = Auth::user();

        $produkId = $request->produkId;
        $variantId = $request->variantId;

        $produk = Cart::where('user_id', $user->id)->where('produk_id', $produkId)->where('variant_id', $variantId)->first();
        $produk->delete();

        return response()->json([
            'message' => 'jumlah barang berhasil dikurang'
        ]);
    }

    public function deleteAll(Request $request)
    {
        $user = Auth::user();

        $produkId = $request->produkId;

        $produk = Cart::where('user_id', $user->id)->where('produk_id', $produkId);
        $produk->delete();

        return response()->json([
            'message' => 'barang sudah dihapus dari keranjang'
        ]);
    }

    public function checkout()
    {
        $user = Auth::user();

        $data = Toko::with('getProdukCheckout')
            ->join('produk', 'produk.toko_id', 'tokos.id')
            ->join('variants', 'variants.product_id', 'produk.id')
            ->join('carts', 'carts.variant_id', 'variants.id')
            ->whereHas('getProdukCheckout')
            ->where('carts.status', 'true')
            ->select(['tokos.id', 'tokos.img_profile', 'tokos.nama_toko', DB::raw('sum(variants.harga_variant) as total'), DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
            * cos(radians(tokos.latitude)) 
            * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
            + sin(radians(" . $user->latitude . ")) 
            * sin(radians(tokos.latitude))) * 3000 as ongkir")])
            ->groupBy(['tokos.id', 'carts.status'])
            ->get();

        $subtotal = Toko::with('getProdukCheckout')
            ->join('produk', 'produk.toko_id', 'tokos.id')
            ->join('variants', 'variants.product_id', 'produk.id')
            ->join('carts', 'carts.variant_id', 'variants.id')
            ->whereHas('getProdukCheckout')
            ->where('carts.status', 'true')
            ->select(DB::raw('sum(variants.harga_variant) as total'))
            ->groupBy(['carts.status'])
            ->value('total');

        $distance = DB::table('tokos')
            ->join('produk', 'produk.toko_id', 'tokos.id')
            ->join('variants', 'variants.product_id', 'produk.id')
            ->join('carts', 'carts.variant_id', 'variants.id')
            ->where('carts.status', 'true')
            ->select([DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
            * cos(radians(tokos.latitude)) 
            * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
            + sin(radians(" . $user->latitude . ")) 
            * sin(radians(tokos.latitude))) * 3000 as ongkir")])
            ->groupBy(['tokos.id', 'carts.status'])
            ->get();

        $ongkir = $distance->sum('ongkir');

        return response()->json([
            'data' => $data,
            'rincian' => [
                'subtotalProduk' => $subtotal,
                'subtotalOngkir' => $ongkir,
                'totalKeseluruhan' => ''
            ]
        ]);
    }
}
