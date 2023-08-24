<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Toko;
use App\Models\User;
use Carbon\Carbon;
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

            if ($lastConvo) {
                $dataJoin->conversation_id = $conversation_id;
                $dataJoin->lastConvo = $lastConvo ?? "";

                return $dataJoin;
            } else {
                return null;
            }
        })->filter();

        $final = $convos->values()->toArray();

        return response()->json([
            'status' => 200,
            'message' => 'List percakapan seller',
            'list' => $final
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

        $convo = Message::where('conversation_id', $conversationId)
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'messages.user_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('users', 'users.id', '=', 'messages.user_id')
            ->select('messages.*', 'users.name', 'users.photo', 'roles.name as role')
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
            'messages' => $itemsWithStatus ?? [],
        ]);
    }

    // pov user

    public function start(Request $request)
    {
        $interlocutor = $request->interlocutor;
        $user = Auth::user();

        $result = Conversation::where(function ($query) use ($user, $interlocutor) {
            $query->where(function ($subquery) use ($user, $interlocutor) {
                $subquery->where('person_one', $user->id)
                    ->where('person_two', $interlocutor);
            })->orWhere(function ($subquery) use ($user, $interlocutor) {
                $subquery->where('person_one', $interlocutor)
                    ->where('person_two', $user->id);
            });
        })->first();

        if ($result) {
            return response()->json([
                'status' => 200,
                'convo_id' => $result->id,
                'res' => $result
            ]);
        } else {
            $newConvo = Conversation::create([
                'person_one' => $user->id,
                'person_two' => $interlocutor,
                'updated_at' => Carbon::now()
            ]);

            return response()->json([
                'status' => 200,
                'convo_id' => $newConvo->id,
            ]);
        }
    }


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
            $lastConvo = Message::where('conversation_id', $conversation_id)->select('messages.id', 'messages.conversation_id', 'messages.user_id', 'messages.message', DB::raw('DATE_FORMAT(messages.created_at, "%Y-%m-%d %H:%i:%s") as terakhir_dikirim'))->orderBy('updated_at', 'DESC')->first();

//            $dataJoin->conversation_id = $conversation_id;
//            $dataJoin->lastConvo = $lastConvo ?? "";

            if ($lastConvo) {
                $dataJoin->conversation_id = $conversation_id;
                $dataJoin->lastConvo = $lastConvo ?? "";

                return $dataJoin;
            } else {
                return null;
            }
        })->filter();

        $final = $convos->values()->toArray();

        return response()->json([
            'status' => 200,
            'message' => 'List percakapan user',
            'list' => $final
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

        $convo = Message::where('conversation_id', $conversationId)
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'messages.user_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('users', 'users.id', '=', 'messages.user_id')
            ->select('messages.*', 'users.name', 'users.photo', 'roles.name as role')
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
            'messages' => $itemsWithStatus,
        ]);
    }

}
