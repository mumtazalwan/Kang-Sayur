<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleSession;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use Carbon\Carbon;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $mytime = Carbon::now()->format('H:i:s');
        $mydate = Carbon::now()->format('Y.m.d');

        $session = Sale::with('product')
            ->join('sale_sessions', 'sale_sessions.id', '=', 'sales.session_id')
            ->join('statuses', 'statuses.produk_id', '=', 'sales.produk_id')
            ->where('statuses.status', '=', 'Accepted')
            ->whereTime('sale_sessions.start', '<=', $mytime)
            ->whereTime('sale_sessions.end', '>=', $mytime)
            ->whereDate('created_at', '=', $mydate)
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
