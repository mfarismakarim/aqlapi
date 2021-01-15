<?php

namespace App\Events;
use App\Models\Donation;

class NewDonatorHasRegisteredEvent extends Event
{
    public $donator;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Donation $donator)
    {
        $this->donator = $donator;
    }
}
