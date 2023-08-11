<?php

namespace App\Http\Controllers;

use App\Models\Inbox;
use App\Models\Order;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InboxController extends Controller
{
    public function listInbox()
    {
        $sellerId = Auth::user();
        $tokoId = Toko::where('seller_id', $sellerId->id)->value('id');

        $data = Inbox::where('user_id', $tokoId)
            ->select('inboxes.user_id', 'inboxes.judul', 'inboxes.body', 'inboxes.image', DB::raw('DATE_FORMAT(inboxes.created_at, "%Y-%m-%d") as tanggal'))
            ->get();

        return response()->json([
            'status' => '200',
            'message' => 'list inbox',
            'data' => $data
        ]);
    }
}
