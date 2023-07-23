<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index()
    {
        //
    }

    public function review(Request $request)
    {
        $idUser = Auth::user()->id;
        $request->validate([
            'rating' => 'required',
            'comment' => 'required',
            'img_product' => 'file|image|mimes:png,jpg,jpeg|max:3048',
            'product_id' => 'required',
            'toko_id' => 'required',
            'variant_id' => 'required',
            'transaction_code' => 'required'
        ]);

        if ($request->img_product) {
            // store photo
            $timestamp = time();
            $photoName = $timestamp . $request->img_product->getClientOriginalName();
            $path = '/user_profile/' . $photoName;
            Storage::disk('public')->put($path, file_get_contents($request->img_product));

            Review::create([
                'id_user' => $idUser,
                'rating' => request('rating'),
                'img_product' => '/storage' . $path,
                'comment' => request('comment'),
                'product_id' => request('product_id'),
                'toko_id' => request('toko_id'),
                'variant_id' => request('variant_id'),
                'transaction_code' => request('transaction_code'),
            ]);
        } else {
            Review::create([
                'id_user' => $idUser,
                'rating' => request('rating'),
                'comment' => request('comment'),
                'product_id' => request('product_id'),
                'toko_id' => request('toko_id'),
                'variant_id' => request('variant_id'),
                'transaction_code' => request('transaction_code'),
            ]);
        }

        return response()->json([
            'status' => '200',
            'message' => 'berhasil memberikan penilaian',
        ]);
    }
}
