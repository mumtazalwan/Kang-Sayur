<?php

namespace App\Http\Controllers\Seller;

use App\Events\VerifiyProductNotification;
use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use App\Models\Produk;
use App\Models\Review;
use App\Models\LogVisitor;
use App\Models\Status;
use App\Models\Toko;
use App\Models\Variant;
use Illuminate\Support\Facades\Storage;

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
        $user = Auth::user();

        $data = Produk::where('nama_produk', 'LIKE', '%' . $keyword . '%')
            ->join('statuses', 'statuses.produk_id', '=', 'produk.id')
            ->join('variants', 'variants.product_id', '=', 'produk.id')
            ->join('tokos', 'tokos.id', '=', 'produk.toko_id')
            ->where('statuses.status', '=', 'Accepted')
            ->groupBy('produk.id')
            ->select('produk.id',
                'tokos.nama_toko',
                'produk.nama_produk',
                'variants.variant',
                'variants.variant_img as image',
                'variants.harga_variant as harga',
                DB::raw("6371 * acos(cos(radians(" . $user->latitude . "))
                * cos(radians(tokos.latitude))
                * cos(radians(tokos.longitude) - radians(" . $user->longitude . "))
                + sin(radians(" . $user->latitude . "))
                * sin(radians(tokos.latitude))) AS distance"))
            ->get();

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
                'data' => []
            ]);
        }
    }

    // seller create produk
    public function create(Request $request)
    {
        $user = Auth::user();
        $tokoId = DB::table('tokos')->select('tokos.id')->where('tokos.seller_id', $user->id)->value('id');
        $variant = $request->variant;

        $request->validate([
            'nama_produk' => 'required|string',
            'kategori_id' => 'required|integer',
            'variant_img' => 'file|image|mimes:png,jpg,jpeg|max:3048'
        ]);

        $id = mt_rand(1000000, 9999999);

        $produk = Produk::create([
            'nama_produk' => request('nama_produk'),
            'kategori_id' => request('kategori_id'),
            'toko_id' => $tokoId,
            'ulasan_id' => $id
        ]);

        Status::create([
            'produk_id' => $produk->id,
            'toko_id' => $tokoId
        ]);

        foreach ($variant as $key) {
            $variant_img = $key['images'];
            $timestamp = time();
            $photoName = $timestamp . $variant_img->getClientOriginalName();
            $path = '/user_profile/' . $photoName;
            Storage::disk('public')->put($path, file_get_contents($variant_img));

            $data = Variant::create([
                'product_id' => $produk->id,
                'variant_img' => '/storage' . $path,
                'variant' => $key['variant'],
                'variant_desc' => $key['variant_desc'],
                'stok' => $key['stok'],
                'harga_variant' => $key['harga_variant']
            ]);
        }

        return response()->json([
            'status_code' => '200',
            'message' => 'Data berhasil ditambahkan, mohon menunggu antrean verifikasi barang oleh admin',
            'data' => $data ?? [],
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
        $user = Auth::user();
        $tokoId = Produk::where('id', $produkId)->value('toko_id');

        $data = Produk::where('produk.id', $produkId)
            ->with(['variant', 'review'])
            ->join('tokos', 'tokos.id', '=', 'toko_id')
            ->join('statuses', 'statuses.produk_id', '=', 'produk.id')
            ->where('statuses.status', '=', 'Accepted')
            ->first();

        $appendImg = Variant::where('variants.product_id', $produkId)->value('variant_img');
        $appendHarga = Variant::where('variants.product_id', $produkId)->value('harga_variant');

        $data->image = $appendImg;
        $data->harga = $appendHarga;

        LogVisitor::create([
            'product_id' => $produkId,
            'user_id' => Auth::user()->id,
        ]);

        $produksStore = Produk::join('tokos', 'tokos.id', '=', 'toko_id')
            ->where('produk.toko_id', $tokoId)
            ->join('statuses', 'statuses.produk_id', '=', 'produk.id')
            ->join('variants', 'variants.product_id', '=', 'produk.id')
            ->where('statuses.status', '=', 'Accepted')
            ->whereNotIn('produk.id', [$produkId])
            ->select('produk.*', 'tokos.*', 'variants.*', DB::raw("6371 * acos(cos(radians(" . $user->latitude . "))
            * cos(radians(tokos.latitude))
            * cos(radians(tokos.longitude) - radians(" . $user->longitude . "))
            + sin(radians(" . $user->latitude . "))
            * sin(radians(tokos.latitude))) as distance"))
            ->get();

        $data->toko_ini = $produksStore;

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

        $data = DB::table('produk')
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
                'variants.harga_variant')
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
            ->select(
                'produk.nama_produk',
                'produk.id as produk_id',
                'variants.variant_img',
                'variants.harga_variant',
                'produk.created_at as tanggal_verivikasi',
                'statuses.status',
                'variants.stok',
                'variants.variant'
            )
            ->where('produk.toko_id', $tokoId)
            ->join('statuses', 'statuses.produk_id', '=', 'produk.id')
            ->join('variants', 'variants.product_id', '=', 'produk.id')
            ->groupBy('produk.id')
            ->where('statuses.status', 'Accepted')
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
            ->join('kategori', 'kategori.id', '=', 'produk.kategori_id')
            ->select(
                'produk.nama_produk',
                'produk.id as produk_id',
                'variants.variant_img',
                'variants.harga_variant',
                'produk.created_at as tanggal_verivikasi',
                'statuses.status',
                'variants.stok',
                'variants.variant',
                'variants.variant_desc',
                'kategori.nama_kategori'
            )
            ->where('produk.toko_id', $tokoId)
            ->join('statuses', 'statuses.produk_id', '=', 'produk.id')
            ->join('variants', 'variants.product_id', '=', 'produk.id')
            ->where('statuses.status', 'Pending')
            ->get();

        return response()->json([
            'status_code' => '200',
            'message' => 'Verifikasi',
            'data' => $data,
        ]);
    }

    // admin verifikasi list
    public function verifikasi_list()
    {
        $data = Produk::with('variant')
            ->join('statuses', 'statuses.produk_id', '=', 'produk.id')
            ->where('statuses.status', 'Pending')
            ->get();

        return response()->json([
            'status_code' => '200',
            'message' => 'List verifikasi',
            'data' => $data,
        ]);
    }

    // admin ubah status verifikasi
    public function verifikasi(Request $request)
    {
        $produkId = $request->produkId;
        $toko_id = Produk::where('id', $produkId)->first();
        $nama_toko = Toko::where('id', $toko_id->toko_id)->first();
        $seller = User::where('id', $nama_toko->seller_id)->first();

        Status::where('statuses.produk_id', $produkId)
            ->update([
                'status' => 'Accepted'
            ]);

        $fcmservicekey = "AAAAyKjEhRs:APA91bEhFcJBjxY6U-I-eXoHFLVrdWE1WAVaI9ZhsGjFfpfdmRDdL1s8Mc7HLSptWJVB_i1gyluUaa22r0Q6mXxQ8gVRepRNgyoJjCnDG4Jdi6DgMgOo-CiX8017bV_pY2oVuTN0OVUi";
        $headers = [
            'Authorization: key=' . $fcmservicekey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        $data = [
            "registration_ids" => [$seller->device_token],
            "notification" => [
                "title" => "Produk Verifikasi",
                "body" => "HI Toko $nama_toko->nama_toko, Produk anda sudah di verifikasi oleh admin kami loh",
                "content_available" => true,
                "priority" => "high",
            ],
        ];
        $dataString = json_encode($data);

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        Log::info($response);

        if ($response === false) {
            Log::error(curl_error($ch));
        }

        curl_close($ch);

        return response()->json([
            'status_code' => '200',
            'message' => "berhasil di verifikasi",
            'data' => $data,
            'user' => $seller
        ]);
    }
}
