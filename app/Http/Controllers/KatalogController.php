<?php

namespace App\Http\Controllers;

use App\Models\Katalog;
use Illuminate\Http\Request;

use App\Models\Produk;

class KatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $katalogId = $request->katalogId;

        $products = Produk::where('katalog_id', $katalogId)->get();

        return response()->json([
            'status_code' => 'succes',
            'message' => 'List Product Toko',
            'data' => $products->setHidden([ 'deskripsi', 'katalog_id', 'kategori_id', 'varian_id', 'ulasan_id', 'created_at', 'updated_at']),
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
    public function show(Katalog $katalog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Katalog $katalog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Katalog $katalog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Katalog $katalog)
    {
        //
    }
}
