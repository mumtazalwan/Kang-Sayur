<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
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
            ->select(DB::raw('COUNT(carts.produk_id) as inCart'), 'carts.status')
            ->first();

        if ($customInpt > $currentProduct->inCart) {
            if ($currentProduct->status == 'false') {
                for ($i = $currentProduct->inCart; $i < $customInpt; $i++) {
                    Cart::create([
                        'user_id' => $user->id,
                        'produk_id' => $produkId,
                        'variant_id' => $variantId,
                        'toko_id' => $tokoId,
                        'status' => 'false',
                    ]);
                }

                return response()->json([
                    'message' => 'jumlah barang berhasil ditabah sebelumnya false'
                ]);
            } else {
                for ($i = $currentProduct->inCart; $i < $customInpt; $i++) {
                    Cart::create([
                        'user_id' => $user->id,
                        'produk_id' => $produkId,
                        'variant_id' => $variantId,
                        'toko_id' => $tokoId,
                        'status' => 'true',
                    ]);
                }
                return response()->json([
                    'message' => 'jumlah barang berhasil ditabah sebelumnya true'
                ]);

            }
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

    public function checkout(Request $request)
    {
        $alamatId = $request->alamatId;
        $user = Auth::user();

        if ($alamatId) {
            $alamat = Address::where('user_id', $user->id)
                ->where('id', $alamatId)->first();
        } else {
            $alamat = Address::where('user_id', $user->id)
                ->where('prioritas_alamat', "Utama")->first();
        }

        $data = Toko::with('getProdukCheckout')
            ->join('produk', 'produk.toko_id', 'tokos.id')
            ->join('variants', 'variants.product_id', 'produk.id')
            ->join('carts', 'carts.variant_id', 'variants.id')
            ->whereHas('getProdukCheckout')
            ->where('carts.status', 'true')
            ->select(['tokos.id', 'tokos.img_profile', 'tokos.nama_toko', DB::raw('sum(variants.harga_variant) as total'), DB::raw("6371 * acos(cos(radians(" . $alamat->latitude . "))
            * cos(radians(tokos.latitude))
            * cos(radians(tokos.longitude) - radians(" . $alamat->longitude . "))
            + sin(radians(" . $alamat->latitude . "))
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
            ->select([DB::raw("6371 * acos(cos(radians(" . $alamat->latitude . "))
            * cos(radians(tokos.latitude))
            * cos(radians(tokos.longitude) - radians(" . $alamat->longitude . "))
            + sin(radians(" . $alamat->latitude . "))
            * sin(radians(tokos.latitude))) * 3000 as ongkir")])
            ->groupBy(['tokos.id', 'carts.status'])
            ->get();

        $ongkir = $distance->sum('ongkir');

        return response()->json([
            'status_code' => '200',
            'messgae' => 'Data checkout',
            'info_pengiriman' => [
                'alamat_id' => $alamat->id,
                'nama' => $alamat->nama_penerima,
                'nomot_telepon' => $alamat->nomor_hp,
                'alamat' => $alamat->alamat_lengkap
            ],
            'data' => $data,
            'rincian' => [
                'subtotalProduk' => $subtotal,
                'subtotalOngkir' => $ongkir,
                'totalKeseluruhan' => $subtotal + $ongkir
            ]
        ]);
    }

    public function instantbuy(Request $request)
    {
        $user = Auth::user();

        $alamatId = $request->alamatId;
        $tokoId = $request->tokoId;
        $produkId = $request->produkId;
        $variantId = $request->variantid;
        $jumlahBeli = $request->jumlahBeli;

        if ($alamatId) {
            $alamat = Address::where('user_id', $user->id)
                ->where('id', $alamatId)->first();
        } else {
            $alamat = Address::where('user_id', $user->id)
                ->where('prioritas_alamat', "Utama")->first();
        }

        $data = Toko::join('produk', 'produk.toko_id', 'tokos.id')
            ->join('variants', 'variants.product_id', 'produk.id')
            ->where('tokos.id', $tokoId)
            ->where('produk.id', $produkId)
            ->where('variants.id', $variantId)
            ->select(['tokos.id as toko_id',
                'tokos.img_profile',
                'tokos.nama_toko',
                'tokos.kota',
                'produk.id as produk_id',
                'produk.nama_produk',
                'variants.id as variant_id',
                'variants.variant',
                'variants.harga_variant',
                'variants.variant_img as gambar_produk'])
            ->first();

        $ongkir = Toko::join('produk', 'produk.toko_id', 'tokos.id')
            ->join('variants', 'variants.product_id', 'produk.id')
            ->where('tokos.id', $tokoId)
            ->where('produk.id', $produkId)
            ->where('variants.id', $variantId)
            ->select(DB::raw("6371 * acos(cos(radians(" . $alamat->latitude . "))
            * cos(radians(tokos.latitude))
            * cos(radians(tokos.longitude) - radians(" . $alamat->longitude . "))
            + sin(radians(" . $alamat->latitude . "))
            * sin(radians(tokos.latitude))) * 3000 as ongkir"))
            ->value('ongkir');

        $subtotal = Toko::join('produk', 'produk.toko_id', 'tokos.id')
            ->join('variants', 'variants.product_id', 'produk.id')
            ->where('tokos.id', $tokoId)
            ->where('produk.id', $produkId)
            ->where('variants.id', $variantId)
            ->select(DB::raw('sum(variants.harga_variant) as total'))
            ->value('total');

        $data->jumlah_beli = $jumlahBeli;
        return response()->json([
            'status_code' => '200',
            'messgae' => 'Data checkout',
            'info_pengiriman' => [
                'alamat_id' => $alamat->id,
                'nama' => $alamat->nama_penerima,
                'nomot_telepon' => $user->phone_number,
                'alamat' => $user->address
            ],
            'data' => $data,
            'rincian' => [
                'subtotalProduk' => $subtotal * $jumlahBeli,
                'subtotalOngkir' => $ongkir,
                'totalKeseluruhan' => ($subtotal * $jumlahBeli) + $ongkir
            ]
        ]);
    }

    public function updateStatus(Request $request)
    {
        $user = Auth::user();
        $produkId = $request->produkId;
        $variantId = $request->variantId;

        $produkC = Cart::where('user_id', $user->id)->where('produk_id', $produkId)->where('variant_id', $variantId)->first()->status;
        $produk = Cart::where('user_id', $user->id)->where('produk_id', $produkId)->where('variant_id', $variantId)->get();

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

    public function selectAll()
    {
        $user = Auth::user();

        $check = Cart::where('user_id', $user->id)->get();
        $cartOn = Cart::where('user_id', $user->id)->where('status', 'true')->get();

        if (count($check) == count($cartOn)) {
            Cart::where('user_id', $user->id)
                ->update([
                    'status' => 'false'
                ]);
        } else {
            Cart::where('user_id', $user->id)
                ->update([
                    'status' => 'true'
                ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil pilih semua cart'
        ]);
    }
}
