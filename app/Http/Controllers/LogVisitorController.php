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
            $data = DB::table('log_visitor')
                ->select('produk.nama_produk', 'produk.id', 'img_id', DB::raw('COUNT(produk.id) as visited'))
                ->join('produk', function (JoinClause $join) {
                    $join->on('log_visitor.product_id', '=', 'produk.id');
                })
                ->join('statuses', 'statuses.produk_id', '=', 'log_visitor.product_id')
                ->where('statuses.status', '=', 'Accepted')
                ->groupBy('nama_produk', 'id', 'img_id')
                ->orderBy('visited', 'DESC')
                ->where('produk.kategori_id', $kategoriId)
                ->get();

            return response()->json([
                'data' => $data
            ]);
        } else {
            $data = DB::table('log_visitor')
                ->select('produk.nama_produk', 'produk.id', 'img_id', DB::raw('COUNT(produk.id) as visited'))
                ->join('produk', function (JoinClause $join) {
                    $join->on('log_visitor.product_id', '=', 'produk.id');
                })
                ->join('statuses', 'statuses.produk_id', '=', 'log_visitor.product_id')
                ->where('statuses.status', '=', 'Accepted')
                ->groupBy('nama_produk', 'id', 'img_id')
                ->orderBy('visited', 'DESC')
                ->get();

            return response()->json([
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
                ->select('produk.nama_produk', 'produk.id', 'img_id', DB::raw('COUNT(produk.id) as visited'))
                ->join('produk', function (JoinClause $join) {
                    $join->on('log_visitor.product_id', '=', 'produk.id');
                })
                ->join('statuses', 'statuses.produk_id', '=', 'log_visitor.product_id')
                ->where('statuses.status', '=', 'Accepted')
                ->where('log_visitor.user_id', '=', $user->id)
                ->groupBy('nama_produk', 'id', 'img_id')
                ->orderBy('visited', 'DESC')
                ->where('produk.kategori_id', $kategoriId)
                ->get();

            $theta = $user;

            return response()->json([
                'data' => $data,
                'theta' => $theta
            ]);
        } else {
            $user = Auth::user();
            $data = DB::table('log_visitor')
                ->select('produk.nama_produk', 'produk.id', 'img_id', 'tokos.longitude as longitude', DB::raw('COUNT(produk.id) as visited'))
                ->join('produk', function (JoinClause $join) {
                    $join->on('log_visitor.product_id', '=', 'produk.id');
                })
                ->join('statuses', 'statuses.produk_id', '=', 'log_visitor.product_id')
                ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
                ->where('statuses.status', '=', 'Accepted')
                ->groupBy('nama_produk', 'id', 'img_id', 'tokos.longitude')
                ->orderBy('visited', 'DESC')
                ->first();

            $data2 = DB::table('log_visitor')
                ->select(
                    'produk.nama_produk',
                    'produk.id',
                    'img_id',
                    DB::raw('COUNT(produk.id) as visited'),
                    DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
                * cos(radians(tokos.latitude)) 
                * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
                + sin(radians(" . $user->latitude . ")) 
                * sin(radians(tokos.latitude))) AS distance")
                )
                ->join('produk', function (JoinClause $join) {
                    $join->on('log_visitor.product_id', '=', 'produk.id');
                })
                ->join('statuses', 'statuses.produk_id', '=', 'log_visitor.product_id')
                ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
                ->where('statuses.status', '=', 'Accepted')
                ->groupBy('nama_produk', 'id', 'img_id', 'tokos.longitude', 'tokos.latitude')
                ->orderBy('visited', 'DESC')
                ->get();

            return response()->json([
                'user' => $data2,
            ]);
        }
    }
}
