<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VerifiyProductNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $tokoId;
    public $namaToko;
    public $message;

    public function __construct($tokoId, $message, $namaToko)
    {
        $this->tokoId = $tokoId;
        $this->message = $message;
        $this->namaToko = $namaToko;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('verifikasi-produk-seller.' . $this->tokoId),
        ];
    }

    public function broadcastWith()
    {
        return [
            'toko_id' => $this->tokoId,
            'nama_toko' => $this->namaToko,
            'message' => $this->message
        ];
    }

    public function broadcastAs()
    {
        return "notifVerifikasi";
    }
}
