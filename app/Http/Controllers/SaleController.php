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

        $session = Sale::with('getSession')
        ->join('sale_sessions', 'sale_sessions.id', '=', 'sales.session_id')
        ->join('statuses', 'statuses.produk_id', '=', 'sales.produk_id')
        ->where('statuses.status', '=', 'Accepted')
        ->select('sale_sessions.start', 'sales.*',)
        ->whereTime('sale_sessions.start', '<=', $mytime) 
        ->whereTime('sale_sessions.end', '>=', $mytime)
        ->whereDate('created_at', '=', $mydate) 
        ->get();

        Sale::whereDate( 'created_at', '<', $mydate)->delete();

        return response()->json([
            'status' => 'succes',
            'message' => 'List Sale',
            'data' => $session
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
