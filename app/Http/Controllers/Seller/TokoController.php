<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\LogVisitor;
use Illuminate\Http\Request;

use App\Models\Toko;
use App\Models\Produk;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function sellerPersonalInformation()
    {
        $user = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $user->id)->value('id');

        $data = DB::table('tokos')
            ->select('tokos.id', 'tokos.nama_toko', 'tokos.img_profile', 'users.name', 'users.email', 'users.phone_number')
            ->join('users', 'users.id', '=', 'tokos.seller_id')
            ->where('tokos.seller_id', $user->id)
            ->first();

        return response()->json([
            'status' => 200,
            'message' => "Seller personal information",
            'data' => $data,
        ]);
    }


    public function index()
    {
        // list toko

        $data = DB::table('tokos')->select('*')->get();

        return response()->json([
            'status_code' => '200',
            'message' => 'List Toko',
            'data' => $data,
            // 'data' => $data->setHidden(['deskripsi', 'alamat', 'location', 'open', 'close', 'catalogue_id', 'created_at', 'updated_at', 'seller_id']),
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
                'tokos.img_profile',
                'tokos.nama_toko',
                'tokos.alamat',
                'tokos.longitude',
                'tokos.latitude',
                DB::raw("6371 * acos(cos(radians(tokos.latitude)) 
            * cos(radians(" . $user->latitude . ")) 
            * cos(radians(" . $user->longitude . ") - radians(tokos.longitude)) 
            + sin(radians(tokos.latitude)) 
            * sin(radians(" . $user->latitude . "))) as distance"),
                'tokos.nama_toko'
            )
            ->having('distance', '<=', $radius)
            ->groupBy('id', 'tokos.longitude', 'tokos.latitude', 'tokos.nama_toko', 'tokos.alamat',)
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
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $user->id)->value('id');

        $pengunjung = DB::table('log_visitor')
            ->select(
                DB::raw('COUNT(toko_id) as visited'),
            )
            ->where('toko_id', $tokoId)
            ->first();

        $order_count = DB::table('transactions')
            ->join('orders', 'orders.transaction_code', '=', 'transactions.transaction_code')
            ->where('orders.store_id', $tokoId)
            ->get();

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

    public function income(Request $request)
    {
        $user = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $user->id)->value('id');

        $custom = $request->custom;
        $today = Carbon::now()->format('Y.m.d');
        $thisMonth = Carbon::now()->format('m');
        $thisYear = Carbon::now()->format('Y');

        $three_month_back = $thisMonth - 3;
        $six_month_back = $thisMonth - 6;
        $one_year_back = $thisYear - 1;

        if ($three_month_back <= 0) {
            $three_month_back = 1;
        }

        if ($six_month_back <= 0) {
            $six_month_back = 1;
        }

        switch ($custom) {
                // bulan ini
            case '1':
                $pemasukan_custom = DB::table('orders')
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->where('orders.store_id', $tokoId)
                    ->whereMonth('orders.created_at', $thisMonth)
                    ->whereYear('orders.created_at', $thisYear)
                    ->where('orders.status', 'Selesai')
                    ->select(DB::raw('SUM(produk.harga_produk) as total'))->value('total');
                break;

                // 3 bulan terakhir
            case '2':
                $pemasukan_custom = DB::table('orders')
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->where('orders.store_id', $tokoId)
                    ->whereMonth('orders.created_at', '<=', $thisMonth)
                    ->whereMonth('orders.created_at', '>=', $three_month_back)
                    ->whereYear('orders.created_at', $thisYear)
                    ->where('orders.status', 'Selesai')
                    ->select(DB::raw('SUM(produk.harga_produk) as total'))->value('total');
                break;

                // 6 bulan terakhir
            case '3':
                $pemasukan_custom = DB::table('orders')
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->where('orders.store_id', $tokoId)
                    ->whereMonth('orders.created_at', '<=', $thisMonth)
                    ->whereMonth('orders.created_at', '>=', $six_month_back)
                    ->whereYear('orders.created_at', $thisYear)
                    ->where('orders.status', 'Selesai')
                    ->select(DB::raw('SUM(produk.harga_produk) as total'))->value('total');
                break;

                // 1 tahun terakhir
            case '4':
                $pemasukan_custom = DB::table('orders')
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->where('orders.store_id', $tokoId)
                    ->whereYear('orders.created_at', '<=', $thisYear)
                    ->whereYear('orders.created_at', '>=', $one_year_back)
                    ->where('orders.status', 'Selesai')
                    ->select(DB::raw('SUM(produk.harga_produk) as total'))->value('total');
                break;

            default:
                $pemasukan_custom = DB::table('orders')
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->where('orders.store_id', $tokoId)
                    ->whereDate('orders.created_at', $today)
                    ->where('orders.status', 'Selesai')
                    ->select(DB::raw('SUM(produk.harga_produk) as total'))->value('total');
                break;
        }

        $order_count = DB::table('orders')
            ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
            ->join('produk', 'produk.id', '=', 'orders.product_id')
            ->where('orders.store_id', $tokoId)
            ->where('orders.status', 'Selesai')
            ->select(DB::raw('SUM(produk.harga_produk) as total'))->value('total');

        return response()->json([
            'status' => 200,
            'pemasukan' => [
                'total_keseluruhan' => $order_count,
                'pemasukan_pilihan' => $pemasukan_custom,
            ],
        ]);
    }

    public function graphic(Request $request)
    {
        $user = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $user->id)->value('id');

        $custom = $request->custom;
        $today = Carbon::now()->format('Y.m.d');
        $thisMonth = Carbon::now()->format('m');
        $thisYear = Carbon::now()->format('Y');

        $three_month_back = $thisMonth - 3;
        $six_month_back = $thisMonth - 6;
        $one_year_back = $thisYear - 1;

        if ($three_month_back < 0) {
            $three_month_back = 1;
        }

        if ($six_month_back < 0) {
            $six_month_back = 1;
        }
        switch ($custom) {
                // 3 bulan terakhir
            case '1':
                $order_count = DB::table('orders')
                    ->select(
                        DB::raw("DATE_FORMAT(orders.created_at, '%d-%b-%Y') as date"),
                        DB::raw('SUM(produk.harga_produk) as total')
                    )
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->where('orders.store_id', $tokoId)
                    ->where('orders.status', 'Selesai')
                    ->whereMonth('orders.created_at', '<=', $thisMonth)
                    ->whereMonth('orders.created_at', '>=', $three_month_back)
                    ->whereYear('orders.created_at', $thisYear)
                    ->groupBy('date')
                    ->get();
                break;

                // 6 bulan terakhir
            case '2':
                $order_count = DB::table('orders')
                    ->select(
                        DB::raw("DATE_FORMAT(orders.created_at, '%d-%b-%Y') as date"),
                        DB::raw('SUM(produk.harga_produk) as total')
                    )
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->where('orders.store_id', $tokoId)
                    ->where('orders.status', 'Selesai')
                    ->whereMonth('orders.created_at', '<=', $thisMonth)
                    ->whereMonth('orders.created_at', '>=', $six_month_back)
                    ->whereYear('orders.created_at', $thisYear)
                    ->groupBy('date')
                    ->get();
                break;

                // 1 tahun terakhir
            case '3':
                $order_count = DB::table('orders')
                    ->select(
                        DB::raw("DATE_FORMAT(orders.created_at, '%b-%Y') as date"),
                        DB::raw('SUM(produk.harga_produk) as total')
                    )
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->where('orders.store_id', $tokoId)
                    ->where('orders.status', 'Selesai')
                    ->whereYear('orders.created_at', '<=', $thisYear)
                    ->whereYear('orders.created_at', '>=', $one_year_back)
                    ->groupBy('date')
                    ->get();
                break;

            case '4':
                $order_count = DB::table('orders')
                    ->select(
                        DB::raw("DATE_FORMAT(orders.created_at, '%b-%Y') as date"),
                        DB::raw('SUM(produk.harga_produk) as total')
                    )
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->where('orders.store_id', $tokoId)
                    ->where('orders.status', 'Selesai')
                    ->groupBy('date')
                    ->get();
                break;

            default:
                $order_count = DB::table('orders')
                    ->select(
                        DB::raw("DATE_FORMAT(orders.created_at, '%d-%b-%Y') as date"),
                        DB::raw('SUM(produk.harga_produk) as total')
                    )
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->where('orders.store_id', $tokoId)
                    ->where('orders.status', 'Selesai')
                    ->whereMonth('orders.created_at', $thisMonth)
                    ->whereYear('orders.created_at', $thisYear)
                    ->groupBy('date')
                    ->get();
                break;
        }

        return response()->json([
            'status' => 200,
            'grafik_penjualan' => $order_count

        ]);
    }
}
