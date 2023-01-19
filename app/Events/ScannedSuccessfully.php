<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScannedSuccessfully
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $log_id;
    public $request;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($log_id, $request)
    {
        $this->log_id = $log_id;
        $this->request = $request;
    }
}
