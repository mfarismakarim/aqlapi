<?php

namespace App\Events;

class NewDonatorHasRegisteredEvent extends Event
{
    public $donator;
    public $secondParameter;
    public $status;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($donator, $secondParameter, $status)
    {
        $this->donator = $donator;
        $this->secondParameter = $secondParameter;
        $this->status = $status;
    }
}
