<?php

namespace App\Http\Controllers;

use App\Events\OrderTracking;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Delivery extends Controller
{

    //ubah status menjadi sedang diantar + create new event
    public function takeOrder(Request $request)
    {
        $request->validate([
            'transaction_code' => 'required',
            'user_id' => 'required',
            'store_id' => 'required'
        ]);

        $tokoId = Toko::where('seller_id', Auth::user()->id);

        broadcast(new OrderTracking($tokoId, '00', '00'));
    }

    // ubah status menjadi selesai
    public function finishOrder()
    {

    }
}
