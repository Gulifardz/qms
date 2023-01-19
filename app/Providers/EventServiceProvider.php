<?php

namespace App\Providers;

use App\Events\DriverSuccessfullyCreated;
use App\Events\DriverSuccessfullyDeleted;
use App\Events\ScannedSuccessfully;
use App\Events\SuccessfulQuarryRegistration;
use App\Events\TruckCrudEvent;
use App\Listeners\DeleteDriverImage;
use App\Listeners\SaveBoughtProducts;
use App\Listeners\SaveDriverImage;
use App\Listeners\TagProductToQuarry;
use App\Listeners\TagTruckDrivers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        DriverSuccessfullyCreated::class => [
            SaveDriverImage::class
        ],
        DriverSuccessfullyDeleted::class => [
            DeleteDriverImage::class
        ],
        TruckCrudEvent::class => [
            TagTruckDrivers::class
        ],
        ScannedSuccessfully::class => [
            SaveBoughtProducts::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
