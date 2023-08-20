<?php

namespace App\Http\Controllers;

use App\Events\Messages;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        $message = $request->message;
        $user = Auth::user();
        $conversationId = $request->conversationId;

        Message::create([
            'conversation_id' => $conversationId,
            'user_id' => $user->id,
            'message' => $message
        ]);

        Conversation::where('id', $conversationId)
            ->update([
                'updated_at' => Carbon::now()
            ]);

        $list = Conversation::where('id', $conversationId)
            ->select(DB::raw("CASE WHEN person_one <> $user->id THEN person_one ELSE person_two END AS interlocutors"))
            ->first();

        $device_token = User::where('id', $list->interlocutors)->first();


        $fcmservicekey = "AAAAyKjEhRs:APA91bEhFcJBjxY6U-I-eXoHFLVrdWE1WAVaI9ZhsGjFfpfdmRDdL1s8Mc7HLSptWJVB_i1gyluUaa22r0Q6mXxQ8gVRepRNgyoJjCnDG4Jdi6DgMgOo-CiX8017bV_pY2oVuTN0OVUi";
        $headers = [
            'Authorization: key=' . $fcmservicekey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        $data = [
            "registration_ids" => [$device_token->device_token],
            "notification" => [
                "title" => "Ada pesan masuk nih",
                "body" => $message,
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

        event(new Messages($conversationId));

        return response()->json([
            'status' => 200,
            'message' => 'berhasil mengirim pesan'
        ]);
    }
}
