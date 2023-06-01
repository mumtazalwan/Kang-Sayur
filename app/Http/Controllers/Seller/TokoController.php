<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\LogVisitor;
use Illuminate\Http\Request;

use App\Models\Toko;
use App\Models\Produk;
use App\Models\Transaction;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
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
            'status_code' => '200',
            'message' => 'List Toko',
            'data' => $data->setHidden(['deskripsi', 'alamat', 'location', 'open', 'close', 'catalogue_id', 'created_at', 'updated_at', 'seller_id']),
        ]);
    }

    public function detail_toko(Request $request)
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

        LogVisitor::create([
            'toko_id' => $tokoId,
            'user_id' => Auth::user()->id,
        ]);

        return response()->json([
            'status_code' => '200',
            'message' => 'Detail toko',
            'data' => $detail,
        ]);
    }

    public function produk(Request $request)
    {
        $kategoriId = $request->kategoriId;
        $tokoId = $request->tokoId;

        $data = Produk::where('kategori_id', $kategoriId)->where('toko_id', $tokoId)->get();

        return response()->json([
            'status_code' => '200',
            'message' => 'List produk kategori',
            'data' => $data->setHidden(['id', 'deskripsi', 'toko_id', 'id_onsale', 'created_at', 'updated_at', 'kategori_id', 'katalog_id', 'varian_id', 'ulasan_id', 'is_onsale']),
        ]);
    }

    public function detail_produk(Request $request)
    {
        $produkId = $request->produkId;

        $data = Produk::where('id', $produkId)->first();

        return response()->json([
            'status_code' => '200',
            'message' => 'Detail produk kategori',
            'data' => $data,
        ]);
    }

    public function getNearestStore()
    {
        $radius = 25;
        $user = Auth::user();

        $data = DB::table('tokos')
            ->select(
                'tokos.id',
                'tokos.nama_toko',
                DB::raw("6371 * acos(cos(radians(" . $user->latitude . ")) 
            * cos(radians(tokos.latitude)) 
            * cos(radians(tokos.longitude) - radians(" . $user->longitude . ")) 
            + sin(radians(" . $user->latitude . ")) 
            * sin(radians(tokos.latitude))) as distance"),
                'tokos.nama_toko'
            )
            ->having('distance', '<=', $radius)
            ->groupBy('id', 'tokos.longitude', 'tokos.latitude', 'tokos.nama_toko')
            ->orderBy('distance', 'ASC')
            ->get();

        return response()->json([
            'status_code' => '200',
            'message' => 'Toko terdekat',
            'data' => $data,
        ]);
    }

    public function analysis()
    {
        $user = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $user->id)->first();

        $pengunjung = DB::table('log_visitor')
            ->select(
                DB::raw('COUNT(toko_id) as visited'),
            )
            ->where('toko_id', 1)
            ->first();

        $order_count = DB::table('transactions')
            ->join('orders', 'orders.transaction_code', '=', 'transactions.transaction_code')
            ->where('orders.store_id', 1)
            ->groupBy('orders.transaction_code')->get();

        return response()->json([
            'status' => 200,
            'data' => [
                'pesanan' => count($order_count),
                'pengunjung_toko' => $pengunjung->visited,
                'rating_produk' => 4.5,
                'produk_terjual' => 100,
                'laporan' => 0,
                'rating_pelayanan' => 5
            ],
        ]);
    }
}
