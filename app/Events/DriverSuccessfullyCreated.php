<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverSuccessfullyCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $name;
    public $request;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($name, $request)
    {
        $this->name = $name;
        $this->request = $request;
    }
}
