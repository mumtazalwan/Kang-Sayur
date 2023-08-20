<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{

    // pov seller

    public function list()
    {
        $user = Auth::user();

        $list = Conversation::where(function ($query) use ($user) {
            $query->where('person_one', $user->id)
                ->orWhere('person_two', $user->id);
        })
            ->select(DB::raw("CASE WHEN person_one <> $user->id THEN person_one ELSE person_two END AS interlocutors"), 'id')
            ->orderBy('updated_at', 'DESC')
            ->get();

        $convos = $list->map(function ($list) {
            $otherId = $list->interlocutors;
            $conversation_id = $list->id;
            $dataJoin = User::where('id', $otherId)->select('users.id as user_id', 'users.name', 'users.photo')->first();
            $lastConvo = Message::where('conversation_id', $conversation_id)->orderBy('created_at', 'DESC')->first();

            $dataJoin->conversation_id = $conversation_id;
            $dataJoin->lastConvo = $lastConvo ?? "";

            return $dataJoin;
        });

        return response()->json([
            'status' => 200,
            'message' => 'List percakapan user',
            'list' => $convos
        ]);
    }

    public function roomChat(Request $request)
    {
        $user = Auth::user();
        $conversationId = $request->conversationId;

        $list = Conversation::where(function ($query) use ($user) {
            $query->where('person_one', $user->id)
                ->orWhere('person_two', $user->id);
        })
            ->select(DB::raw("CASE WHEN person_one <> $user->id THEN person_one ELSE person_two END AS interlocutors"))
            ->first();

        $interlocutors = User::where('id', $list->interlocutors)->first();

        $convo = Message::where('conversation_id', $conversationId)
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'messages.user_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('messages.*', 'roles.name as role')
            ->orderBy('messages.updated_at')
            ->get();

        $lastConvo = Message::where('conversation_id', $conversationId)->orderBy('updated_at', 'DESC')->first();

        $itemsWithStatus = [];

        foreach ($convo as $convos) {
            if ($convos->updated_at == $lastConvo->updated_at) {
                $convos->isCurrentMessage = true;
            } else {
                $convos->isCurrentMessage = false;
            }

            $itemsWithStatus = $convo;
        }

        return response()->json([
            'status' => 200,
            'message' => 'Roomchat',
            'interlocutors' => $interlocutors,
            'messages' => $itemsWithStatus,
        ]);
    }

    // pov user

    public function listUser()
    {
        $user = Auth::user();

        $list = Conversation::where(function ($query) use ($user) {
            $query->where('person_one', $user->id)
                ->orWhere('person_two', $user->id);
        })
            ->select(DB::raw("CASE WHEN person_one <> $user->id THEN person_one ELSE person_two END AS interlocutors"), 'id')
            ->orderBy('updated_at', 'DESC')
            ->get();

        $convos = $list->map(function ($list) {
            $otherId = $list->interlocutors;
            $conversation_id = $list->id;
            $dataJoin = Toko::where('seller_id', $otherId)->select('tokos.id as toko_id', 'tokos.nama_toko', 'tokos.img_profile')->first();
            $lastConvo = Message::where('conversation_id', $conversation_id)->orderBy('updated_at', 'DESC')->first();

            $dataJoin->conversation_id = $conversation_id;
            $dataJoin->lastConvo = $lastConvo ?? "";

            return $dataJoin;
        });

        return response()->json([
            'status' => 200,
            'message' => 'List percakapan user',
            'list' => $convos
        ]);
    }

    public function roomChatUser(Request $request)
    {
        $user = Auth::user();
        $conversationId = $request->conversationId;

        $list = Conversation::where(function ($query) use ($user) {
            $query->where('person_one', $user->id)
                ->orWhere('person_two', $user->id);
        })->select(DB::raw("CASE WHEN person_one <> $user->id THEN person_one ELSE person_two END AS interlocutors"))
            ->first();

        $interlocutors = Toko::where('seller_id', $list->interlocutors)->first();

        $convo = Message::where('conversation_id', $conversationId)
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'messages.user_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('messages.*', 'roles.name as role')
            ->orderBy('messages.updated_at')
            ->get();

        $lastConvo = Message::where('conversation_id', $conversationId)->orderBy('updated_at', 'DESC')->first();

        $itemsWithStatus = [];

        foreach ($convo as $convos) {
            if ($convos->updated_at == $lastConvo->updated_at) {
                $convos->isCurrentMessage = true;
            } else {
                $convos->isCurrentMessage = false;
            }

            $itemsWithStatus = $convo;
        }

        return response()->json([
            'status' => 200,
            'message' => 'Roomchat',
            'interlocutors' => $interlocutors,
            'messages' => $itemsWithStatus,
        ]);
    }

}
