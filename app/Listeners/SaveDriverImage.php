<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class SaveDriverImage
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
        $request = $event->request;
    
        Storage::disk('gcs')->put('seeder/' . $name, file_get_contents($request->file('picture')));
    }
}
