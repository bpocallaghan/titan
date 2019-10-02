<?php

namespace Bpocallaghan\Titan\Events;

use Bpocallaghan\Titan\Events\Event;
use Bpocallaghan\Titan\Models\FeedbackContactUs;

class ContactUsFeedback extends BaseEvent
{
    /**
     * Create a new event instance.
     *
     * @param FeedbackContactUs $row
     */
    public function __construct(FeedbackContactUs $row)
    {
        $row->type = 'Contact Us';
        $this->eloquent = $row;

        log_activity('Contact Us', "{$row->fullname} submitted a contact us.", $row);
    }
}
