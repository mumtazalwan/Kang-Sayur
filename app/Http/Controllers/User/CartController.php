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

        $list = Toko::with('getProductCart')
            ->whereHas('getProductCart')
            ->select(['tokos.id', 'tokos.img_profile', 'tokos.nama_toko', 'tokos.alamat as alamat_toko'])
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
            ->where('carts.toko_id', $tokoId)
            ->where('carts.produk_id', $produkId)
            ->where('carts.variant_id', $variantId)
            ->groupBy('carts.produk_id')
            ->select(DB::raw('COUNT(carts.produk_id) as inCart'))
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
        $variantId = $request->variantId;

        $produk = Cart::where('user_id', $user->id)->where('produk_id', $produkId)->where('variant_id', $variantId);
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
                'totalKeseluruhan' => $subtotal + $ongkir
            ]
        ]);
    }

    public function updateStatus(Request $request)
    {
        $user = Auth::user();
        $produkId = $request->produkId;
        $variantId = $request->variantId;
        $cartId = $request->cartId;

        $produkC = Cart::where('user_id', $user->id)->where('produk_id', $produkId)->where('variant_id', $variantId)->first()->status;
        $produk = Cart::where('user_id', $user->id)->where('produk_id', $produkId)->where('variant_id', $variantId)->get();

        // return response()->json([
        //     'print' => $produkC
        // ]);

        if ($produkC == "true") {
            $produk->toQuery()->update(array("status" => "false"));
        } else {
            $produk->toQuery()->update(array("status" => "true"));
        }
    }

    public function selected()
    {
        $user = Auth::user();
        $subtotal = Cart::where('user_id', $user->id)
            ->where('status', 'true')
            ->join('variants', 'variants.id', '=', 'carts.variant_id')
            ->select(DB::raw('SUM(variants.harga_variant) as subtotal'))
            ->first();

        if ($subtotal) {
            return response()->json([
                'status' => '200',
                'message' => 'subtotal cart yang dipilih',
                'subtotal' => $subtotal->subtotal
            ]);
        } else {
            return response()->json([
                'status' => '200',
                'message' => 'subtotal cart yang dipilih',
                'subtotal' => 0
            ]);
        }
    }
}
