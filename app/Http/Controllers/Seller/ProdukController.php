<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Models\Produk;
use App\Models\Review;
use App\Models\LogVisitor;
use App\Models\Status;
use App\Models\Toko;

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
        $data = Produk::where('nama_produk', 'LIKE', '%' . $keyword . '%')
            ->join('statuses', 'statuses.produk_id', '=', 'produk.id')
            ->where('statuses.status', '=', 'Accepted')->get();

        if (count($data)) {
            return response()->json([
                'status' => '200',
                'message' => 'List produk',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => '200',
                'message' => 'List produk',
                'data' => 'Data tidak ditemukan'
            ]);
        }
    }

    // public function detail(Request $request)
    // {
    //     $produkId = $request->produkId;
    //     $data = Produk::where('id', $produkId)
    //         ->with(['review' => function ($u) {
    //             $u
    //                 ->select('users.name', 'reviews.*', DB::raw('COUNT(like_comments.review_id) as count_like'))
    //                 ->join('users', 'users.id', '=', 'reviews.id_user')
    //                 ->join('like_comments', 'like_comments.review_id', '=', 'reviews.id')
    //                 ->groupBy('users.name', 'reviews.id_user', 'reviews.id', 'reviews.rating', 'reviews.img_product', 'reviews.comment', 'reviews.product_id', 'reviews.toko_id', 'reviews.created_at', 'reviews.updated_at', 'like_comments.review_id')
    //                 ->get();
    //         }])
    //         ->get();

    // LogVisitor::create([
    //     'product_id' => $produkId,
    //     'user_id' => Auth::user()->id,
    // ]);

    //     return response()->json([
    //         'status' => '200',
    //         'message' => 'Detail Toko',
    //         'data' => $data
    //     ]);
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $tokoId = Toko::where('seller_id', $user->id)->first();

        $request->validate([
            'nama_produk' => 'required|string',
            'deskripsi' => 'required',
            'kategori_id' => 'required|integer',
            'harga_produk' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'stok_produk' => 'required|integer',

        ]);

        $id = mt_rand(1000000, 9999999);

        $produk = Produk::create([
            'nama_produk' => request('nama_produk'),
            'deskripsi' => request('deskripsi'),
            'kategori_id' => request('kategori_id'),
            'harga_produk' => request('harga_produk'),
            'stok_produk' => request('stok_produk'),
            'toko_id' => $tokoId->id,
            'ulasan_id' => $id
        ]);

        Status::create([
            'produk_id' => $produk->id,
            'toko_id' => $tokoId->id
        ]);

        return response()->json([
            'status_code' => '200',
            'message' => 'Data berhasil ditambahkan, mohon menunggu antrean verifikasi barang',
        ]);
    }

    public function produk(Request $request)
    {
        $kategoriId = $request->kategoriId;
        $tokoId = $request->tokoId;

        $data = Produk::where('kategori_id', $kategoriId)->where('toko_id', $tokoId)->get();

        return response()->json([
            'status_code' => '200',
            'message' => 'List Produk Kategori',
            'data' => $data->setHidden(['id', 'deskripsi', 'toko_id', 'id_onsale', 'created_at', 'updated_at', 'kategori_id', 'katalog_id', 'varian_id', 'ulasan_id', 'is_onsale']),
        ]);
    }

    public function detail_produk(Request $request)
    {
        $produkId = $request->produkId;

        $data = Produk::with('review')->where('produk.id', $produkId)
            ->join('statuses', 'statuses.produk_id', '=', 'produk.id')
            ->where('statuses.status', '=', 'Accepted')
            ->first();

        LogVisitor::create([
            'product_id' => $produkId,
            'user_id' => Auth::user()->id,
        ]);

        return response()->json([
            'status_code' => '200',
            'message' => 'List Produk Kategori',
            'data' => $data,
        ]);
    }

    public function Categories(Request $request)
    {
        $kategoriId = $request->kategoriId;

        $data = kategori::all();

        return response()->json([
            'status_code' => '200',
            'message' => 'List kategori',
            'data' => $data,
        ]);
    }

    public function produkByCategory(Request $request)
    {
        $kategoriId = $request->kategoriId;

        $data = Produk::where('kategori_id', $kategoriId)
            // ->join('statuses', 'statuses.produk_id', '=', 'produk.id')
            // ->where('statuses.status', '=', 'Accepted')
            ->get();

        return response()->json([
            'status_code' => '200',
            'message' => 'List produk berdasarkan kategori',
            'data' => $data,
        ]);
    }

    public function listProduct()
    {
        $data = Produk::where('produk.toko_id', 2)
            ->join('statuses', 'statuses.produk_id', '=', 'produk.id')
            ->where('statuses.status', '=', 'Accepted')
            ->get();

        return response()->json([
            'status_code' => '200',
            'message' => 'Daftar produk',
            'data' => $data,
        ]);
    }

    public function onVerify()
    {
        $data = Produk::where('produk.toko_id', 2)
            ->join('statuses', 'statuses.produk_id', '=', 'produk.id')
            ->where('statuses.status', '=', 'Pending')
            ->get();

        return response()->json([
            'status_code' => '200',
            'message' => 'Verifikasi',
            'data' => $data,
        ]);
    }
}
