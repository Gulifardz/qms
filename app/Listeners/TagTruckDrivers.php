<?php

namespace App\Listeners;

use App\Models\TruckDriver;

class TagTruckDrivers
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $company_id = $event->company_id;
        $truck_id = $event->truck_id;
        $drivers = $event->drivers;

        TruckDriver::where(['company_id' => $company_id, 'truck_id' => $truck_id])->delete();

        foreach ($drivers as $driver) {
            $truck_driver = new TruckDriver([
                'company_id' => $company_id,
                'truck_id' => $truck_id,
                'driver_id' => $driver
            ]);

            $truck_driver->save();
        }
    }
}
