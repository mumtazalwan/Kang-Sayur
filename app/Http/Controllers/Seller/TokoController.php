<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toko;
use App\Models\Katalog;

use Illuminate\Support\Facades\DB;

class TokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // $selectionList = DB::table('tokos')
        // ->leftJoin('katalogs', 'id_katalog', '=', 'tokos.katalog_id')
        // ->select('katalogs.id_kategori')
        // ->orderBy('katalogs.id_katalog','asc')
        // ->take(100)
        // ->get();

        // $subQuery1 = DB::table('produk')
        // ->where('toko_id', '=', 'kategori_id')
        // ->groupBy('kategori_id');

        // $subQuery2 = DB::table('kategori');

        // $subQuery = DB::table('produk')
        // ->where('toko_id', '=', 1)
        // ->groupBy('kategori_id');

        // $mainQuery = DB::table('kategori')
        // ->joinSub($subQuery, 'kategori', function ($join) {
        //     $join->on('produk.kategori_id', '=', 'kategori.id');
        // })->get();

        // tabel katagori -> get kategori berdasarkan produk.kategori_id and toko_id

        // $getProduk = DB::table('produk')
        // ->where('toko_id', '=', 1)
        // ->groupBy('kategori_id');

        // $getKategori = DB::tabel('kategori', 'C');

        // $getKategori = DB::table('kategori')
        // ->joinSub($getProduk, 'P', function ($join){
        //     $join->on('P.kategori_id', '=', 'kategori.id');
        // })->get();

        $que = DB::table('kategori')
            ->select(DB::raw("FROM(SELECT * FROM `produk` WHERE toko_id = 1 GROUP BY kategori_id) AS P, JOIN (SELECT * FROM kategori) AS C, ON ON P.kategori_id = C.id"))
            ->get();

        // list toko
        $list_toko = Toko::with('katalog')->get();

        return response()->json([
            'status_code' => 'succes',
            'message' => 'List Toko',
            'data' => $list_toko,
            'gatau' => $que
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
