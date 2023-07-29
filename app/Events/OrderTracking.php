<?php

namespace App\Events;

use App\Models\Order;
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

class OrderTracking implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $transaction_code;
    public $lat;
    public $long;

    public function __construct($transaction_code, $lat, $long)
    {
        $this->transaction_code = $transaction_code;
        $this->lat = $lat;
        $this->long = $long;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        $userId = Auth::user();
//        $userOrder = Order::where('transaction_code', $transaction_code->id);
        return new PrivateChannel('delivery.' . $userId);
    }

    public function broadcastWith()
    {
        return [
            'lat' => $this->lat,
            'long' => $this->long
        ];
    }

//    public function broadcastAs()
//    {
//        return "updateLoc";
//    }
}
