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

        $list_toko = Toko::all();
        $katalog_toko = Katalog::where('id_katalog','=', '1')->get();

        return response()->json([
            'status_code' => 'succes',
            'message' => 'List Toko',
            'data' => $list_toko,
            'katalog' => $katalog_toko,
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
