<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $otp = rand(1000, 9999);

        $otpExistence = Otp::where('otp', $otp)->get();

        if ($otpExistence) {
            $otp = rand(1000, 9999);
        }

        $expired = Carbon::now()->addMinute(2);

        Mail::send(new OtpMail(request('email'), $otp));

        Otp::create([
            'email' => request('email'),
            'otp' => $otp,
            'expire' => $expired
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'succes',
            'otp' => $otp
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        $now = Carbon::now();

        $dataOtp = Otp::where('otps.email', request('email'))
            ->where('otps.status', '=', 'Waiting')->get();

        if ($dataOtp->status = 'Waiting') {
            Otp::where('otps.email', request('email'))
                ->where('otps.status', '=', 'Waiting')
                ->whereTime('otps.expire', '<=', $now)
                ->update(['status' => 'Expired']);

            $comparation = Otp::where('otps.email', request('email'))
                ->where('otps.status', '=', 'Waiting')->first();

            if ($comparation) {
                $final = Otp::where('otps.email', request('email'))
                    ->where('otps.status', '=', 'Waiting')
                    ->first();

                if ($final->otp == request('otp')) {
                    Otp::where('otps.email', request('email'))
                        ->where('otps.status', '=', 'Waiting')
                        ->update(['status' => 'Accepted']);

                    return response()->json([
                        'status' => 200,
                        'message' => 'Berhasil Verifikasi',
                        'status_verifikasi' => 'Accepted'
                    ]);
                } else {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Kode OTP Salah',
                        'status_verifikasi' => 'Failed'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Kode OTP Expire',
                    'status_verifikasi' => 'Wrong'
                ]);
            }
        }


    }
}
