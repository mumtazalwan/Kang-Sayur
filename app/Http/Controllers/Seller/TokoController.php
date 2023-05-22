<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko;
use App\Models\Produk;

use Illuminate\Support\Facades\DB;

class TokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // list toko

        $data = Toko::all();

        return response()->json([
            'status_code' => 'succes',
            'message' => 'List Toko',
            'data' => $data->setHidden(['deskripsi', 'alamat', 'location', 'open', 'close', 'catalogue_id', 'created_at', 'updated_at', 'seller_id']),
        ]);
    }

    public function detail(Request $request)
    {
        $tokoId = $request->tokoId;

        $detail = DB::table('tokos')
            ->select('tokos.*')
            ->where('tokos.id', $tokoId)
            ->first();

        $kategori = DB::table('produk')
            ->select('kategori.id', 'kategori.nama_kategori')
            ->join('kategori', 'kategori.id', '=', 'produk.kategori_id')
            ->groupBy('kategori.id', 'kategori.nama_kategori')
            ->where('produk.toko_id', $tokoId)
            ->get();

        $detail->category = $kategori;

        return response()->json([
            'status_code' => 'succes',
            'message' => 'List Toko',
            'data' => $detail,
        ]);
    }

    public function produk(Request $request)
    {
        $kategoriId = $request->kategoriId;
        $tokoId = $request->tokoId;

        $data = Produk::where('kategori_id', $kategoriId)->where('toko_id', $tokoId)->get();

        return response()->json([
            'status_code' => 'succes',
            'message' => 'List Produk Kategori',
            'data' => $data->setHidden(['id', 'deskripsi', 'toko_id', 'id_onsale', 'created_at', 'updated_at', 'kategori_id', 'katalog_id', 'varian_id', 'ulasan_id', 'is_onsale']),
        ]);
    }

    public function detail_produk(Request $request)
    {
        $produkId = $request->produkId;

        $data = Produk::where('id', $produkId)->first();

        return response()->json([
            'status_code' => 'succes',
            'message' => 'List Produk Kategori',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
