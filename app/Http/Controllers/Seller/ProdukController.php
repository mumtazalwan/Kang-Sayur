<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\rs;
use Illuminate\Support\Facades\DB;
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

    public function home_search($keyword)
    {
        $data = Produk::where('nama_produk', 'LIKE', '%'. $keyword . '%')->get();

        if(count($data)){
            return response()->json([
                'status' => '200',
                'message' => 'Rangkuman',
                'data' => $data
            ]);
        }else{
            return response()->json([
                'status' => '200',
                'message' => 'Rangkuman',
                'data' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function detail(Request $request)
    {
        $produkId = $request->produkId;
        $data = Produk::where('id', $produkId)
            ->with(['review' => function($u){
                $u
                ->select('users.name', 'reviews.*', DB::raw('COUNT(like_comments.review_id) as count_like'))
                ->join('users', 'users.id', '=', 'reviews.id_user')
                ->join('like_comments', 'like_comments.review_id', '=', 'reviews.id')
                ->groupBy('users.name', 'reviews.id_user', 'reviews.id', 'reviews.rating','reviews.img_product', 'reviews.comment', 'reviews.product_id', 'reviews.toko_id', 'reviews.created_at', 'reviews.updated_at', 'like_comments.review_id')
                ->get();
            }])
            ->get();

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
