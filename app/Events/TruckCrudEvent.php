<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TruckCrudEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $company_id;
    public $truck_id;
    public $drivers;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($company_id, $truck_id, $drivers)
    {
        $this->company_id = $company_id;
        $this->truck_id = $truck_id;
        $this->drivers = $drivers;
    }
}
