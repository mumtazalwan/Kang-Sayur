<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\LogVisitor;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

use App\Models\Toko;
use App\Models\Produk;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\Cast\Double;
use SebastianBergmann\CodeCoverage\Driver\Driver;

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
            ->select('tokos.id',
                'tokos.nama_toko',
                'tokos.img_profile',
                'tokos.alamat',
                'tokos.open',
                'tokos.close',
                'tokos.deskripsi',
                'users.name',
                'users.email',
                'users.phone_number',
                'users.address')
            ->join('users', 'users.id', '=', 'tokos.seller_id')
            ->where('tokos.seller_id', $user->id)
            ->first();

        return response()->json([
            'status' => 200,
            'message' => "Seller personal information",
            'data' => $data,
        ]);
    }

    public function editToko(Request $request)
    {
        $user = Auth::user();
        $tokoId = DB::table('tokos')->where('tokos.seller_id', $user->id)->first();

        $request->validate([
            'nama_toko' => 'required|string',
            'deskripsi_toko' => 'required',
            'address' => 'required',
            'photo_profile' => 'file|image|mimes:png,jpg,jpeg|max:3048',
            'photo_header' => 'file|image|mimes:png,jpg,jpeg|max:3048',
//            'provinsi' => 'required',
//            'kota' => 'required',
            'open' => 'required|date_format:H:i',
            'close' => 'required|date_format:H:i|after:open',
        ]);

        if ($request->photo_profile && $request->photo_header) {

            // store photo profile
            $timestamp = time();
            $photoProfile = $timestamp . $request->photo_profile->getClientOriginalName();
            $pathProfile = '/user_profile/' . $photoProfile;
            Storage::disk('public')->put($pathProfile, file_get_contents($request->photo_profile));

            // store photo header
            $timestamp = time();
            $photoHeader = $timestamp . $request->photo_header->getClientOriginalName();
            $pathHeader = '/user_profile/' . $photoHeader;
            Storage::disk('public')->put($pathHeader, file_get_contents($request->photo_header));

            if ($tokoId->img_profile) {
                $profilePath = public_path($tokoId->img_profile);
                if (File::exists($profilePath)) {
                    File::delete($profilePath);
                }
            }

            if ($tokoId->img_header) {
                $headerPath = public_path($tokoId->img_header);
                if (File::exists($headerPath)) {
                    File::delete($headerPath);
                }
            }

            Toko::where('tokos.id', $tokoId->id)
                ->update([
                    'img_profile' => '/storage' . $pathProfile,
                    'img_header' => '/storage' . $pathHeader,
                    'nama_toko' => request('nama_toko'),
                    'deskripsi' => request('deskripsi_toko'),
                    'seller_id' => $user->id,
                    'alamat' => request('address'),
                    'latitude' => $tokoId->latitude,
                    'longitude' => $tokoId->longitude,
                    'provinsi' => $tokoId->provinsi,
                    'kota' => $tokoId->kota,
                    'open' => request('open'),
                    'close' => request('close')
                ]);
        } else if ($request->photo_profile) {

            // store photo profile
            $timestamp = time();
            $photoProfile = $timestamp . $request->photo_profile->getClientOriginalName();
            $pathProfile = '/user_profile/' . $photoProfile;
            Storage::disk('public')->put($pathProfile, file_get_contents($request->photo_profile));

            if ($tokoId->img_profile) {
                $profilePath = public_path($tokoId->img_profile);
                if (File::exists($profilePath)) {
                    File::delete($profilePath);
                }
            }

            Toko::where('tokos.id', $tokoId->id)
                ->update([
                    'img_profile' => '/storage' . $pathProfile,
                    'img_header' => $tokoId->img_header,
                    'nama_toko' => request('nama_toko'),
                    'deskripsi' => request('deskripsi_toko'),
                    'seller_id' => $user->id,
                    'alamat' => request('address'),
                    'latitude' => $tokoId->latitude,
                    'longitude' => $tokoId->longitude,
                    'provinsi' => $tokoId->provinsi,
                    'kota' => $tokoId->kota,
                    'open' => request('open'),
                    'close' => request('close')
                ]);
        } else if ($request->photo_header) {

            // store photo header
            $timestamp = time();
            $photoHeader = $timestamp . $request->photo_header->getClientOriginalName();
            $pathHeader = '/user_profile/' . $photoHeader;
            Storage::disk('public')->put($pathHeader, file_get_contents($request->photo_header));

            if ($tokoId->img_header) {
                $headerPath = public_path($tokoId->img_header);
                if (File::exists($headerPath)) {
                    File::delete($headerPath);
                }
            }

            Toko::where('tokos.id', $tokoId->id)
                ->update([
                    'img_profile' => $tokoId->img_profile,
                    'img_header' => '/storage' . $pathHeader,
                    'nama_toko' => request('nama_toko'),
                    'deskripsi' => request('deskripsi_toko'),
                    'seller_id' => $user->id,
                    'alamat' => request('address'),
                    'latitude' => $tokoId->latitude,
                    'longitude' => $tokoId->longitude,
                    'provinsi' => $tokoId->provinsi,
                    'kota' => $tokoId->kota,
                    'open' => request('open'),
                    'close' => request('close')
                ]);
        } else {
            Toko::where('tokos.id', $tokoId->id)
                ->update([
                    'img_profile' => $tokoId->img_profile,
                    'img_header' => $tokoId->img_header,
                    'nama_toko' => request('nama_toko'),
                    'deskripsi' => request('deskripsi_toko'),
                    'seller_id' => $user->id,
                    'alamat' => request('address'),
                    'latitude' => $tokoId->latitude,
                    'longitude' => $tokoId->longitude,
                    'provinsi' => $tokoId->provinsi,
                    'kota' => $tokoId->kota,
                    'open' => request('open'),
                    'close' => request('close')
                ]);
        }

        return response()->json([
            'status' => '200',
            'message' => 'berhasil diupdate',
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
        ]);
    }

    public function detail_toko(Request $request)
    {
        $tokoId = $request->tokoId;
        $user = Auth::user();

        $detail = DB::table('tokos')
            ->select('tokos.*', 'users.phone_number')
            ->join('users', 'users.id', '=', 'tokos.seller_id')
            ->where('tokos.id', $tokoId)
            ->first();

        $kategori = DB::table('produk')
            ->select('kategori.id', 'kategori.nama_kategori')
            ->join('kategori', 'kategori.id', '=', 'produk.kategori_id')
            ->groupBy('kategori.id', 'kategori.nama_kategori')
            ->where('produk.toko_id', $tokoId)
            ->get();

        $produk = Produk::where('produk.toko_id', $tokoId)
            ->join('variants', 'variants.product_id', '=', 'produk.id')
            ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
            ->select(['produk.id',
                'variants.variant_img',
                DB::raw("6371 * acos(cos(radians(" . $user->latitude . "))
            * cos(radians(tokos.latitude))
            * cos(radians(tokos.longitude) - radians(" . $user->longitude . "))
            + sin(radians(" . $user->latitude . "))
            * sin(radians(tokos.latitude))) AS distance"),
                'produk.nama_produk',
                'tokos.nama_toko',
                'variants.harga_variant'])
            ->get();

        $rating = Review::where('toko_id', $tokoId)
            ->select(DB::raw('SUM(reviews.rating) / COUNT(reviews.rating) as rating'))
            ->first()->rating;

        $reviewCount = Review::where('toko_id', $tokoId)->get();

        if ($rating == null) {
            $rating = 5;
        }

        $succesPayment = Order::where('store_id', $tokoId)
            ->where('orders.status', 'Selesai')
            ->groupBy('orders.transaction_code')
            ->get();

        $detail->category = $kategori;
        $detail->produk = $produk;
        $detail->rating = doubleval($rating);
        $detail->tingkat_kepuasan = doubleval($rating * 20);
        $detail->transaksi_berhasil = count($succesPayment);
        $detail->diulas_sebanyak = count($reviewCount);

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
            )
            ->having('distance', '<=', $radius)
            ->groupBy('id', 'tokos.longitude', 'tokos.latitude', 'tokos.nama_toko', 'tokos.alamat', 'tokos.img_profile')
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
                    ->join('variants', 'variants.id', '=', 'orders.variant_id')
                    ->where('orders.store_id', $tokoId)
                    ->whereMonth('orders.created_at', $thisMonth)
                    ->whereYear('orders.created_at', $thisYear)
                    ->where('orders.status', 'Selesai')
                    ->select(DB::raw('SUM(variants.harga_variant) as total'))->value('total');
                break;

            // 3 bulan terakhir
            case '2':
                $pemasukan_custom = DB::table('orders')
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->join('variants', 'variants.id', '=', 'orders.variant_id')
                    ->where('orders.store_id', $tokoId)
                    ->whereMonth('orders.created_at', '<=', $thisMonth)
                    ->whereMonth('orders.created_at', '>=', $three_month_back)
                    ->whereYear('orders.created_at', $thisYear)
                    ->where('orders.status', 'Selesai')
                    ->select(DB::raw('SUM(variants.harga_variant) as total'))->value('total');
                break;

            // 6 bulan terakhir
            case '3':
                $pemasukan_custom = DB::table('orders')
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->join('variants', 'variants.id', '=', 'orders.variant_id')
                    ->where('orders.store_id', $tokoId)
                    ->whereMonth('orders.created_at', '<=', $thisMonth)
                    ->whereMonth('orders.created_at', '>=', $six_month_back)
                    ->whereYear('orders.created_at', $thisYear)
                    ->where('orders.status', 'Selesai')
                    ->select(DB::raw('SUM(variants.harga_variant) as total'))->value('total');
                break;

            // 1 tahun terakhir
            case '4':
                $pemasukan_custom = DB::table('orders')
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->join('variants', 'variants.id', '=', 'orders.variant_id')
                    ->where('orders.store_id', $tokoId)
                    ->whereYear('orders.created_at', '<=', $thisYear)
                    ->whereYear('orders.created_at', '>=', $one_year_back)
                    ->where('orders.status', 'Selesai')
                    ->select(DB::raw('SUM(variants.harga_variant) as total'))->value('total');
                break;

            default:
                $pemasukan_custom = DB::table('orders')
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->join('variants', 'variants.id', '=', 'orders.variant_id')
                    ->where('orders.store_id', $tokoId)
                    ->whereDate('orders.created_at', $today)
                    ->where('orders.status', 'Selesai')
                    ->select(DB::raw('SUM(variants.harga_variant) as total'))->value('total');
                break;
        }

        $order_count = DB::table('orders')
            ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
            ->join('produk', 'produk.id', '=', 'orders.product_id')
            ->join('variants', 'variants.id', '=', 'orders.variant_id')
            ->where('orders.store_id', $tokoId)
            ->where('orders.status', 'Selesai')
            ->select(DB::raw('SUM(variants.harga_variant) as total'))->value('total');

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
                        DB::raw('SUM(variants.harga_variant) as total')
                    )
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->join('variants', 'variants.id', '=', 'orders.variant_id')
                    ->where('orders.store_id', $tokoId)
                    ->where('orders.status', 'Selesai')
                    ->whereMonth('orders.created_at', '<=', $thisMonth)
                    ->whereMonth('orders.created_at', '>=', $three_month_back)
                    ->whereYear('orders.created_at', $thisYear)
                    ->groupBy('date')
                    ->orderBy('orders.created_at', 'ASC')
                    ->get();
                break;

            // 6 bulan terakhir
            case '2':
                $order_count = DB::table('orders')
                    ->select(
                        DB::raw("DATE_FORMAT(orders.created_at, '%d-%b-%Y') as date"),
                        DB::raw('SUM(variants.harga_variant) as total')
                    )
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->join('variants', 'variants.id', '=', 'orders.variant_id')
                    ->where('orders.store_id', $tokoId)
                    ->where('orders.status', 'Selesai')
                    ->whereMonth('orders.created_at', '<=', $thisMonth)
                    ->whereMonth('orders.created_at', '>=', $six_month_back)
                    ->whereYear('orders.created_at', $thisYear)
                    ->groupBy('date')
                    ->orderBy('orders.created_at', 'ASC')
                    ->get();
                break;

            // 1 tahun terakhir
            case '3':
                $order_count = DB::table('orders')
                    ->select(
                        DB::raw("DATE_FORMAT(orders.created_at, '%b-%Y') as date"),
                        DB::raw('SUM(variants.harga_variant) as total')
                    )
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->join('variants', 'variants.id', '=', 'orders.variant_id')
                    ->where('orders.store_id', $tokoId)
                    ->where('orders.status', 'Selesai')
                    ->whereYear('orders.created_at', '<=', $thisYear)
                    ->whereYear('orders.created_at', '>=', $one_year_back)
                    ->groupBy('date')
                    ->orderBy('orders.created_at', 'ASC')
                    ->get();
                break;

            case '4':
                $order_count = DB::table('orders')
                    ->select(
                        DB::raw("DATE_FORMAT(orders.created_at, '%b-%Y') as date"),
                        DB::raw('SUM(variants.harga_variant) as total')
                    )
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->join('variants', 'variants.id', '=', 'orders.variant_id')
                    ->where('orders.store_id', $tokoId)
                    ->where('orders.status', 'Selesai')
                    ->groupBy('date')
                    ->orderBy('orders.created_at', 'ASC')
                    ->get();
                break;

            default:
                $order_count = DB::table('orders')
                    ->select(
                        DB::raw("DATE_FORMAT(orders.created_at, '%d-%b-%Y') as date"),
                        DB::raw('SUM(variants.harga_variant) as total')
                    )
                    ->join('transactions', 'transactions.transaction_code', '=', 'orders.transaction_code')
                    ->join('produk', 'produk.id', '=', 'orders.product_id')
                    ->join('variants', 'variants.id', '=', 'orders.variant_id')
                    ->where('orders.store_id', $tokoId)
                    ->where('orders.status', 'Selesai')
                    ->whereMonth('orders.created_at', $thisMonth)
                    ->whereYear('orders.created_at', $thisYear)
                    ->groupBy('date')
                    ->orderBy('orders.created_at', 'ASC')
                    ->get();
                break;
        }

        return response()->json([
            'status' => 200,
            'grafik_penjualan' => $order_count

        ]);
    }

    //Admin

    public function search_toko($keyword)
    {
        $data = Toko::where('nama_toko', 'LIKE', '%' . $keyword . '%')
            ->leftJoin('users', 'users.id', '=', 'tokos.seller_id')
            ->select(
                'tokos.id',
                'tokos.id',
                'tokos.img_profile',
                'tokos.nama_toko',
                'users.name as nama_pemilik',
                'tokos.alamat',
                'tokos.nama_toko'
            )
            ->get();

        if (count($data)) {
            return response()->json([
                'status' => '200',
                'message' => 'List toko',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => '200',
                'message' => 'List toko',
                'data' => []
            ]);
        }
    }

    public function all()
    {
        $data = Toko::leftJoin('log_visitor', 'log_visitor.toko_id', '=', 'tokos.id')
            ->leftJoin('users', 'users.id', '=', 'tokos.seller_id')
            ->select(
                'tokos.id',
                'tokos.img_profile',
                'tokos.nama_toko',
                'users.name as nama_pemilik',
                'tokos.alamat',
                'tokos.nama_toko',
                DB::raw('COUNT(log_visitor.toko_id) as visited'),
            )
            ->groupBy('id', 'img_profile', 'tokos.longitude', 'tokos.latitude', 'tokos.nama_toko', 'toko_id')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'succes',
            'data' => $data
        ]);
    }

    public function detail_toko_admin(Request $request)
    {
        $tokoId = $request->tokoId;

        $data = Toko::where('tokos.id', '=', $tokoId)
            ->leftJoin('users', 'users.id', '=', 'tokos.seller_id')
            ->select(
                'tokos.*',
                'users.name as nama_seller',
                'users.address as alamat_seller',
                'users.photo as photo_seller',
                'users.email as email_seller',
                'users.phone_number',
                'users.latitude as seller_latitude',
                'users.longitude as seller_longitude',
            )->first();

        $kategori = DB::table('produk')
            ->select('kategori.id', 'kategori.nama_kategori')
            ->join('kategori', 'kategori.id', '=', 'produk.kategori_id')
            ->groupBy('kategori.id', 'kategori.nama_kategori')
            ->where('produk.toko_id', $tokoId)
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'detail toko',
            'data' => [
                'seller_info' => [
                    'nama_seller' => $data->nama_seller,
                    'photo_profile' => $data->photo_seller,
                    'email' => $data->email_seller,
                ],
                'toko_info' => [
                    'deksripsi_toko' => $data->deskripsi,
                    'alamat_toko' => $data->alamat,
                    'jam_operasional' => [
                        'open' => $data->open,
                        'close' => $data->close
                    ],
                    'nomor_telepon' => $data->email_seller,
                    'status' => 'Online',
                    'email' => $data->email_seller,
                    'tanggal_bergabung' => $data->created_at->format('d, M Y'),
                    'titik_koordinat' => [
                        'longitude' => $data->longitude,
                        'latitude' => $data->latitude
                    ]
                ],
                'kategori' => $kategori,
            ]
        ]);
    }

    public function list_produk_toko(Request $request)
    {
        $tokoId = $request->tokoId;
        $kategoriId = $request->kategoriId;

        switch ($kategoriId) {
            case '1':
                $produk = Produk::where('produk.toko_id', $tokoId)
                    ->join('variants', 'variants.product_id', '=', 'produk.id')
                    ->where('produk.kategori_id', 1)
                    ->groupBy('produk.id')
                    ->get();
                break;
            case '2':
                $produk = Produk::where('produk.toko_id', $tokoId)
                    ->join('variants', 'variants.product_id', '=', 'produk.id')
                    ->where('produk.kategori_id', 2)
                    ->groupBy('produk.id')
                    ->get();
                break;
            case '3':
                $produk = Produk::where('produk.toko_id', $tokoId)
                    ->join('variants', 'variants.product_id', '=', 'produk.id')
                    ->where('produk.kategori_id', 3)
                    ->groupBy('produk.id')
                    ->get();
                break;
            case '4':
                $produk = Produk::where('produk.toko_id', $tokoId)
                    ->join('variants', 'variants.product_id', '=', 'produk.id')
                    ->where('produk.kategori_id', 4)
                    ->groupBy('produk.id')
                    ->get();
                break;
            case '5':
                $produk = Produk::where('produk.toko_id', $tokoId)
                    ->join('variants', 'variants.product_id', '=', 'produk.id')
                    ->where('produk.kategori_id', 5)
                    ->groupBy('produk.id')
                    ->get();
                break;
            case '6':
                $produk = Produk::where('produk.toko_id', $tokoId)
                    ->join('variants', 'variants.product_id', '=', 'produk.id')
                    ->where('produk.kategori_id', 6)
                    ->groupBy('produk.id')
                    ->get();
                break;

            default:
                $produk = Produk::where('produk.toko_id', $tokoId)
                    ->join('variants', 'variants.product_id', '=', 'produk.id')
                    ->groupBy('produk.id')
                    ->get();
                break;
        }

        return response()->json([
            'status' => '200',
            'message' => 'List produk toko',
            'produk' => $produk
        ]);
    }

    public function list_driver()
    {
        $user = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $user->id)->value('id');

        $drivers = Kendaraan::where('toko_id', $tokoId)
            ->join('users', 'users.id', '=', 'kendaraans.driver_id')
            ->select('users.name as nama_driver',
                'users.phone_number as nomor_telfon',
                'users.photo as foto_driver',
                'kendaraans.nama_kendaraan',
                'kendaraans.nomor_polisi',
                'kendaraans.driver_id')
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'List produk toko',
            'produk' => $drivers
        ]);
    }
}
