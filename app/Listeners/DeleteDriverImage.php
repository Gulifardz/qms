<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Storage;

class DeleteDriverImage
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $name = $event->name;

        Storage::disk('gcs')->delete('drivers/' . $name);
    }
}
