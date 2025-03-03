<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;

class EmailVerified
{

    /**
     * Handle the event.
     *
     * @param Verified $event
     * @return void
     */
    public function handle(Verified $event): void
    {
        // Set the session message
        session()->flash('success', 'Email verification successful.');
    }
}
