<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('delivery.' . $this->transaction_code),
        ];
    }

    public function broadcastWith()
    {
        $userCoordinate = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->where('orders.transaction_code', $this->transaction_code)->first();

        $distance = DB::table('orders')
            ->select(
                DB::raw("6371 * acos(cos(radians(" . $userCoordinate->latitude . "))
            * cos(radians(" . $this->lat . "))
            * cos(radians(" . $this->long . ") - radians(" . $userCoordinate->longitude . "))
            + sin(radians(" . $userCoordinate->latitude . "))
            * sin(radians(" . $this->lat . "))) as distance"))->value('distance');

        if ($distance < 0.05) {
            return [
                'lat' => $this->lat,
                'long' => $this->long,
                'distance' => $distance,
                'delivered' => true
            ];
        } else {
            return [
                'lat' => $this->lat,
                'long' => $this->long,
                'distance' => $distance,
                'delivered' => false
            ];
        }


    }

    public function broadcastAs()
    {
        return "updateLoc";
    }
}
