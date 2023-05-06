<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\rs;
use Illuminate\Http\Request;

use App\Models\Produk;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function detail(Request $request)
    {
        $produkId = $request->produkId;
        $data = Produk::where('id', $produkId)->get();

        return response()->json([
            'status' => 'succes',
            'message' => 'Detail Toko',
            'data' => $data
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(rs $rs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(rs $rs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, rs $rs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(rs $rs)
    {
        //
    }
}
