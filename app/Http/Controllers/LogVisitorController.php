<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogVisitor;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use function PHPSTORM_META\type;

class LogVisitorController extends Controller
{
    // produk paling sering dikunjungi oleh semua user
    public function getProductPopuler(Request $request)
    {

        $kategoriId = $request->kategoriId;

        if ($kategoriId) {
            $user = Auth::user();
            $data = DB::table('log_visitor')
                ->select(
                    'produk.id',
                    'variants.variant_img',
                    DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
                * cos(radians(tokos.latitude)) 
                * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
                + sin(radians(" . $user->latitude . ")) 
                * sin(radians(tokos.latitude))) AS distance"),
                    'produk.nama_produk',
                    'tokos.nama_toko',
                    'variants.harga_variant',
                    DB::raw('COUNT(produk.id) as visited'),
                )
                ->join('produk', 'produk.id', '=', 'log_visitor.product_id')
                ->join('variants', 'variants.product_id', '=', 'produk.id')
                ->join('statuses', 'statuses.produk_id', '=', 'log_visitor.product_id')
                ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
                ->where('statuses.status', '=', 'Accepted')
                ->groupBy('variants.harga_variant')
                // ->groupBy('nama_produk', 'id', 'tokos.longitude', 'tokos.latitude', 'variants.variant_img', 'variants.harga_variant', 'tokos.nama_toko')
                ->orderBy('visited', 'DESC')
                ->where('produk.kategori_id', $kategoriId)
                ->get();

            return response()->json([
                'satus' => 200,
                'message' => 'Produk Terpopuler',
                'data' => $data
            ]);
        } else {
            $user = Auth::user();

            $data = DB::table('log_visitor')
                ->select(
                    'produk.id',
                    'variants.variant_img',
                    DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
                    * cos(radians(tokos.latitude)) 
                    * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
                    + sin(radians(" . $user->latitude . ")) 
                    * sin(radians(tokos.latitude))) AS distance"),
                    'produk.nama_produk',
                    'tokos.nama_toko',
                    'variants.harga_variant',
                    DB::raw('COUNT(produk.id) as visited'),
                )
                ->join('produk', 'produk.id', '=', 'log_visitor.product_id')
                ->join('variants', 'variants.product_id', '=', 'produk.id')
                ->join('statuses', 'statuses.produk_id', '=', 'log_visitor.product_id')
                ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
                ->where('statuses.status', '=', 'Accepted')
                ->groupBy('produk.id')
                // ->groupBy('nama_produk', 'id', 'variants.variant_img',  'tokos.longitude', 'tokos.latitude', 'variants.harga_variant', 'tokos.nama_toko')
                ->orderBy('visited', 'DESC')
                ->get();

            return response()->json([
                'satus' => 200,
                'message' => 'Produk Terpopuler',
                'data' => $data
            ]);
        }
    }

    public function mostPopularStore()
    {
        $user = Auth::user();
        $data = DB::table('log_visitor')
            ->select(
                'tokos.id',
                DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
                    * cos(radians(tokos.latitude)) 
                    * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
                    + sin(radians(" . $user->latitude . ")) 
                    * sin(radians(tokos.latitude))) AS distance"),
                'tokos.nama_toko',
                DB::raw('COUNT(toko_id) as visited'),
            )
            ->join('tokos', 'tokos.id', '=', 'log_visitor.toko_id')
            ->groupBy('id', 'img_profile', 'tokos.longitude', 'tokos.latitude', 'tokos.nama_toko', 'toko_id')
            ->orderBy('visited', 'DESC')
            ->get();

        return response()->json([
            'satus' => 200,
            'message' => 'Produk Terpopuler',
            'title' => 'Toko Paling Banyak Dikunjungi',
            'data' => $data
        ]);
    }

    // produk paling sering dikunjungi oleh auth user
    public function getUserMostVisitor(Request $request)
    {

        $kategoriId = $request->kategoriId;

        if ($kategoriId) {
            $user = Auth::user();
            $data = DB::table('log_visitor')
                ->select(
                    'produk.id',
                    'variants.variant_img',
                    DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
            * cos(radians(tokos.latitude)) 
            * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
            + sin(radians(" . $user->latitude . ")) 
            * sin(radians(tokos.latitude))) AS distance"),
                    'produk.nama_produk',
                    'tokos.nama_toko',
                    'variants.harga_variant',
                    DB::raw('COUNT(produk.id) as visited'),
                )
                ->join('produk', 'produk.id', '=', 'log_visitor.product_id')
                ->join('variants', 'variants.product_id', '=', 'produk.id')
                ->join('statuses', 'statuses.produk_id', '=', 'log_visitor.product_id')
                ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
                ->where('statuses.status', '=', 'Accepted')
                ->groupBy('produk.id')
                // ->groupBy('nama_produk', 'id',  'tokos.longitude', 'variants.variant_img', 'variants.harga_variant', 'tokos.latitude',  'tokos.nama_toko')
                ->orderBy('visited', 'DESC')
                ->where('produk.kategori_id', $kategoriId)
                ->get();

            return response()->json([
                'satus' => 200,
                'message' => 'Produk Yang Paling Sering Kamu Kunjungi',
                'data' => $data,
            ]);
        } else {
            $user = Auth::user();

            $data = DB::table('log_visitor')
                ->select(
                    'produk.id',
                    'variants.variant_img',
                    DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
            * cos(radians(tokos.latitude)) 
            * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
            + sin(radians(" . $user->latitude . ")) 
            * sin(radians(tokos.latitude))) as distance"),
                    'produk.nama_produk',
                    'tokos.nama_toko',
                    'variants.harga_variant',
                    DB::raw('COUNT(produk.id) as visited'),
                )
                ->join('produk', 'produk.id', '=', 'log_visitor.product_id')
                ->join('variants', 'variants.product_id', '=', 'produk.id')
                ->join('statuses', 'statuses.produk_id', '=', 'log_visitor.product_id')
                ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
                ->where('statuses.status', '=', 'Accepted')
                ->where('user_id', '=', $user->id)
                ->groupBy('produk.id')
                // ->groupBy('nama_produk', 'id', 'variants.variant_img', 'tokos.longitude', 'variants.harga_variant', 'tokos.latitude',  'tokos.nama_toko')
                ->orderBy('visited', 'DESC')
                ->get();

            return response()->json([
                'satus' => 200,
                'message' => 'Produk Yang Paling Sering Kamu Kunjungi',
                'data' => $data,
            ]);
        }
    }
}
