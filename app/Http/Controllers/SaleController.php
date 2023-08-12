<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleSession;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = Auth::user();

        $mytime = Carbon::now()->format('H:i:s');
        $mydate = Carbon::now()->format('Y.m.d');

        $session = Sale::join('sale_sessions', 'sale_sessions.id', '=', 'sales.session_id')
            ->join('produk', 'produk.id', '=', 'sales.produk_id')
            ->join('statuses', 'statuses.produk_id', '=', 'sales.produk_id')->join('tokos', 'tokos.id', '=', 'produk.toko_id')
            ->join('variants', 'variants.id', '=', 'sales.variant_id')
            ->where('statuses.status', '=', 'Accepted')
            ->whereTime('sale_sessions.start', '<=', $mytime)
            ->whereTime('sale_sessions.end', '>=', $mytime)
            ->whereDate('sales.created_at', '=', $mydate)
            ->select('produk.id as produk_id',
                'produk.nama_produk',
                'tokos.id as toko_id',
                'tokos.nama_toko',
                'tokos.img_profile as profile_toko',
                'variants.id as variant_id',
                'variants.harga_variant as harga_awal',
                'sales.harga_sale',
                'sales.stok', DB::raw("6371 * acos(cos(radians(" . $user->latitude . "))
                * cos(radians(tokos.latitude))
                * cos(radians(tokos.longitude) - radians(" . $user->longitude . "))
                + sin(radians(" . $user->latitude . "))
                * sin(radians(tokos.latitude))) AS distance"),
                DB::raw('100 - (sales.harga_sale/(variants.harga_variant/100)) as diskon')
            )
            ->groupBy('produk.id')
            ->get();

        $time = SaleSession::whereTime('start', '<=', $mytime)
            ->whereTime('end', '>=', $mytime)
            ->first();

        Sale::whereDate('created_at', '<', $mydate)->delete();

        if ($time == null) {
            return response()->json(['message' => 'tidak ada promo kilat']);
        }
        return response()->json([
            'status' => '200',
            'message' => 'List Sale',
            'title' => 'Promo kilat',
            'start' => $time->start,
            'end' => $time->end,
            'data' => $session
        ]);
    }
}
