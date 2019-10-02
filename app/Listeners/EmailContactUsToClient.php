<?php

namespace Bpocallaghan\Titan\Listeners;

use Bpocallaghan\Titan\Mail\ClientContactUs;
use Bpocallaghan\Titan\Events\ContactUsFeedback;

class EmailContactUsToClient
{
    /**
     * Handle the event.
     *
     * @param  ContactUsFeedback $event
     * @return void
     */
    public function handle(ContactUsFeedback $event)
    {
        $data = $event->eloquent;

        \Mail::send(new ClientContactUs($data));
    }
}
