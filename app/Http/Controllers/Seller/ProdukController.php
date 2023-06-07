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
use App\Models\Variant;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $data = Produk::with(['variant', 'review'])->get();

        // return response()->json([
        //     'status' => '200',
        //     'message' => 'List produk',
        //     'data' => $data
        // ]);
    }

    // search all produk
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

    // seller create produk
    public function create(Request $request)
    {
        $user = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $user->id)->value('id');

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
            'toko_id' => $tokoId,
            'ulasan_id' => $id
        ]);

        Status::create([
            'produk_id' => $produk->id,
            'toko_id' => $tokoId
        ]);

        return response()->json([
            'status_code' => '200',
            'message' => 'Data berhasil ditambahkan, mohon menunggu antrean verifikasi barang',
        ]);
    }

    //list produk berdasarkan kategori id dan toko id
    public function produkStoreByCategoryId(Request $request)
    {
        $kategoriId = $request->kategoriId;
        $tokoId = $request->tokoId;

        $data = Produk::where('kategori_id', $kategoriId)->where('toko_id', $tokoId)->get();

        if (count($data)) {
            return response()->json([
                'status_code' => '200',
                'message' => 'List produk berdasarkan kategori',
                'data' => $data->setHidden(['id', 'deskripsi', 'toko_id', 'id_onsale', 'created_at', 'updated_at', 'kategori_id', 'katalog_id', 'varian_id', 'ulasan_id', 'is_onsale']),
            ]);
        } else {
            return response()->json([
                'status_code' => '200',
                'message' => 'Tidak ada produk di kategori ini!',
            ]);
        }
    }

    // detail produk
    public function detail_produk(Request $request)
    {
        $produkId = $request->produkId;

        $data = Produk::with(['variant', 'review'])->where('produk.id', $produkId)
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

    // list kategori
    public function categories(Request $request)
    {
        $kategoriId = $request->kategoriId;

        $data = kategori::all();

        return response()->json([
            'status_code' => '200',
            'message' => 'List kategori',
            'data' => $data,
        ]);
    }

    //list produk terdekat berdasarkan kategori id
    public function nearestProdukByCategoryId(Request $request)
    {
        $kategoriId = $request->kategoriId;
        $user = Auth::user();

        $data =  DB::table('produk')
            ->select(
                'produk.id',
                'produk.nama_produk',
                'tokos.alamat',
                DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
                * cos(radians(tokos.latitude)) 
                * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
                + sin(radians(" . $user->latitude . ")) 
                * sin(radians(tokos.latitude))) AS distance"),
                'variants.variant_img',
                'variants.harga_variant',
            )
            ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
            ->join('variants', 'variants.product_id', '=', 'produk.id')
            ->groupBy('produk.id')
            ->orderBy('distance', 'ASC')
            ->where('produk.kategori_id', $kategoriId)
            ->get();

        if (count($data)) {
            return response()->json([
                'status_code' => '200',
                'message' => 'List produk terdekat berdasarkan kategori id',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status_code' => '200',
                'message' => 'Maaf tidak ada produk dengan jarak toko kurang dari 25 km berdasarkan kategori id ini',
            ]);
        }
    }

    // list produk seller yang sudah di acc admin
    public function listProduct()
    {

        $user = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $user->id)->value('id');

        $data = DB::table('produk')
            ->select('tokos.id', 'tokos.nama_toko', 'produk.nama_produk', 'produk.harga_produk', 'tokos.alamat', 'produk.img_id', 'produk.deskripsi', 'produk.stok_produk',)
            ->where('produk.toko_id', $tokoId)
            ->join('statuses', 'statuses.produk_id', '=', 'produk.id')
            ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
            ->where('statuses.status', '=', 'Accepted')
            ->get();

        return response()->json([
            'status_code' => '200',
            'message' => 'Daftar produk',
            'data' => $data,
        ]);
    }

    // list produk seller yang belum di acc admin
    public function onVerify()
    {
        $user = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $user->id)->value('id');

        $data = DB::table('produk')
            ->select('tokos.id', 'tokos.nama_toko', 'produk.nama_produk', 'produk.harga_produk', 'tokos.alamat', 'produk.img_id', 'produk.deskripsi', 'produk.stok_produk', 'produk.created_at as tanggal_verivikasi', 'statuses.status')
            ->where('produk.toko_id', $tokoId)
            ->join('statuses', 'statuses.produk_id', '=', 'produk.id')
            ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
            ->where('statuses.status', '=', 'Pending')
            ->get();

        return response()->json([
            'status_code' => '200',
            'message' => 'Verifikasi',
            'data' => $data,
        ]);
    }
}
