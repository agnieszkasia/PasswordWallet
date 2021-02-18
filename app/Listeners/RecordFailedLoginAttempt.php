<?php

namespace App\Listeners;

use App\FailedLoginAttempt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Failed;

class RecordFailedLoginAttempt
{
    public function handle(Failed $event)
    {
        FailedLoginAttempt::record(
            $event->user,
            $event->credentials['name'],
            request()->ip()
        );
    }
}
