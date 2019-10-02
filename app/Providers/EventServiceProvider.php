<?php

namespace Bpocallaghan\Titan\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // auth
        'Bpocallaghan\Titan\Events\UserRegistered' => [],

        // log actions
        'Bpocallaghan\Titan\Events\ActivityWasTriggered' => [
            'Bpocallaghan\Titan\Listeners\SaveActivity',
        ],
        'Bpocallaghan\Titan\Events\ContactUsFeedback' => [
            'Bpocallaghan\Titan\Listeners\EmailContactUsToClient',
            'Bpocallaghan\Titan\Listeners\EmailContactUsToAdmin',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
