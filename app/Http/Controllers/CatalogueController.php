<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Models\Produk;
use App\Http\Requests\StoreCatalogueRequest;
use App\Http\Requests\UpdateCatalogueRequest;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kategoriId = $request->kategoriId;
        $tokoId = $request->tokoId;

        $list_produk = Produk::where('toko_id', $tokoId)->where('kategori_id', $kategoriId)->get();

        return response()->json([
            'satatus' => 'succes',
            'message' => 'list product',
            'data' => $list_produk
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
    public function store(StoreCatalogueRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Catalogue $catalogue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Catalogue $catalogue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCatalogueRequest $request, Catalogue $catalogue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Catalogue $catalogue)
    {
        //
    }
}
