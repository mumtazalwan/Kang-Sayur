<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Toko;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $thisYear = Carbon::now()->format('Y');

        $tugas_toko = Toko::all();
        $jumlah_pengguna = User::all();
        $pengiriman_berlangsung = Order::where('status', 'Sedang dikirim')->get();
        $pendaftaran_toko = Toko::select(DB::raw('DATE_FORMAT(created_at, "%Y-%M") as bulan'), DB::raw('COUNT(id) as jumlah_pendaftaran'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y")'), $thisYear)
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'Admin dashboard data',
            'top_dashboard' => [
                'jumlah_toko' => count($tugas_toko),
                'produk_verifikasi' => 20,
                'jumlah_pengguna' => count($jumlah_pengguna),
                'pengiriman_berlangsung' => count($pengiriman_berlangsung)
            ],
            'pendaftaran_toko' => $pendaftaran_toko
        ]);
    }
}
