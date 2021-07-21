<?php

namespace App\Modules\Chat\Listeners;

use Carbon\Carbon;
use App\Modules\Account\User\Models\User;
use App\Modules\Chat\Events\UserSuspended;
use App\Modules\Chat\Jobs\LiftSuspension;


class StartSuspension
{
    /**
     * Handle the event.
     *
     * @param UserSuspended $event
     * @return void
     */
    public function handle(UserSuspended $event)
    {
        $user = $event->user;
        $suspensionPeriod = Carbon::now()->addHours(6);
        $user->suspension_period = $suspensionPeriod;
        $user->save();

        LiftSuspension::dispatch($user)->delay(now()->addHours(6));
    }
}