<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogVisitor;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;
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
                    'img_id',
                    DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
                * cos(radians(tokos.latitude)) 
                * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
                + sin(radians(" . $user->latitude . ")) 
                * sin(radians(tokos.latitude))) AS distance"),
                    'produk.nama_produk',
                    'tokos.nama_toko',
                    'produk.harga_produk',
                    DB::raw('COUNT(produk.id) as visited'),
                )
                ->join('produk', function (JoinClause $join) {
                    $join->on('log_visitor.product_id', '=', 'produk.id');
                })
                ->join('statuses', 'statuses.produk_id', '=', 'log_visitor.product_id')
                ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
                ->where('statuses.status', '=', 'Accepted')
                ->groupBy('nama_produk', 'id', 'img_id', 'tokos.longitude', 'tokos.latitude', 'produk.harga_produk', 'tokos.nama_toko')
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
                    'img_id',
                    DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
                    * cos(radians(tokos.latitude)) 
                    * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
                    + sin(radians(" . $user->latitude . ")) 
                    * sin(radians(tokos.latitude))) AS distance"),
                    'produk.nama_produk',
                    'tokos.nama_toko',
                    'produk.harga_produk',
                    DB::raw('COUNT(produk.id) as visited'),
                )
                ->join('produk', function (JoinClause $join) {
                    $join->on('log_visitor.product_id', '=', 'produk.id');
                })
                ->join('statuses', 'statuses.produk_id', '=', 'log_visitor.product_id')
                ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
                ->where('statuses.status', '=', 'Accepted')
                ->groupBy('nama_produk', 'id', 'img_id', 'tokos.longitude', 'tokos.latitude', 'produk.harga_produk', 'tokos.nama_toko')
                ->orderBy('visited', 'DESC')
                ->get();

            return response()->json([
                'satus' => 200,
                'message' => 'Produk Terpopuler',
                'data' => $data
            ]);
        }
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
                    'img_id',
                    DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
            * cos(radians(tokos.latitude)) 
            * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
            + sin(radians(" . $user->latitude . ")) 
            * sin(radians(tokos.latitude))) AS distance"),
                    'produk.nama_produk',
                    'tokos.nama_toko',
                    'produk.harga_produk',
                    DB::raw('COUNT(produk.id) as visited'),
                )
                ->join('produk', function (JoinClause $join) {
                    $join->on('log_visitor.product_id', '=', 'produk.id');
                })
                ->join('statuses', 'statuses.produk_id', '=', 'log_visitor.product_id')
                ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
                ->where('statuses.status', '=', 'Accepted')
                ->groupBy('nama_produk', 'id', 'img_id', 'tokos.longitude', 'tokos.latitude', 'produk.harga_produk', 'tokos.nama_toko')
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
                    'img_id',
                    DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
            * cos(radians(tokos.latitude)) 
            * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
            + sin(radians(" . $user->latitude . ")) 
            * sin(radians(tokos.latitude))) AS distance"),
                    'produk.nama_produk',
                    'tokos.nama_toko',
                    'produk.harga_produk',
                    DB::raw('COUNT(produk.id) as visited'),
                )
                ->join('produk', function (JoinClause $join) {
                    $join->on('log_visitor.product_id', '=', 'produk.id');
                })
                ->join('statuses', 'statuses.produk_id', '=', 'log_visitor.product_id')
                ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
                ->where('statuses.status', '=', 'Accepted')
                ->where('user_id', '=', $user->id)
                ->groupBy('nama_produk', 'id', 'img_id', 'tokos.longitude', 'tokos.latitude', 'produk.harga_produk', 'tokos.nama_toko')
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
