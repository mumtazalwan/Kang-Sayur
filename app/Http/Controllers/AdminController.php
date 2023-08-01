<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $tugas_toko = Toko::all();
        $jumlah_pengguna = User::all();
        $pengiriman_berlangsung = Order::where('status', 'Sedang dikirim')->get();

        return response()->json([
            'status' => '200',
            'message' => 'Admin dashboard data',
            'top_dashboard' => [
                'jumlah_toko' => count($tugas_toko),
                'produk_verifikasi' => 20,
                'jumlah_pengguna' => count($jumlah_pengguna),
                'pengiriman_berlangsung' => count($pengiriman_berlangsung)
            ],
        ]);
    }
}
