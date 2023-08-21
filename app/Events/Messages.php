<?php

namespace App\Events;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Messages implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $conversationId;

    public function __construct($conversationId)
    {
        $this->conversationId = $conversationId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('conversation.' . $this->conversationId),
        ];
    }

    public function broadcastWith()
    {
        $user = Auth::user();

        $list = Conversation::where(function ($query) use ($user) {
            $query->where('person_one', $user->id)
                ->orWhere('person_two', $user->id);
        })
            ->select(DB::raw("CASE WHEN person_one <> $user->id THEN person_one ELSE person_two END AS interlocutors"))
            ->first();

//        if ($this->interlocutorRole = 'user') {
//            $interlocutors = Toko::where('seller_id', $list->interlocutors)->first();
//        } else if ($this->interlocutorRole = 'seller') {
//            $interlocutors = User::where('id', $list->interlocutors)->first();
//        } else if ($this->interlocutorRole = 'driver') {
//            $interlocutors = User::where('id', $list->interlocutors)->first();
//        }

        $convo = Message::where('conversation_id', $this->conversationId)
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'messages.user_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('users', 'users.id', '=', 'messages.user_id')
            ->select('messages.*', 'users.name', 'users.photo', 'roles.name as role')
            ->orderBy('messages.updated_at')
            ->get();

        $lastConvo = Message::where('conversation_id', $this->conversationId)->orderBy('updated_at', 'DESC')->first();

        $itemsWithStatus = [];

        foreach ($convo as $convos) {
            if ($convos->updated_at == $lastConvo->updated_at) {
                $convos->isCurrentMessage = true;
            } else {
                $convos->isCurrentMessage = false;
            }

            $itemsWithStatus = $convo;
        }

        return [
            'status' => 200,
            'message' => 'Roomchat',
            'messages' => $itemsWithStatus,
        ];
    }

    public function broadcastAs()
    {
        return "getMessagesList";
    }
}
