<?php

namespace App\Modules\Chat\Listeners;

use Carbon\Carbon;
use App\Jobs\BroadcastBan;
use App\Modules\Account\User\Models\User;
use App\Modules\Chat\Events\UserSuspended;
use App\Modules\Chat\Jobs\LiftSuspension;
use Illuminate\Foundation\Bus\DispatchesJobs;



class StartSuspension
{
    use DispatchesJobs;
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

        BroadcastBan::dispatch($event->user);

        $job = (new LiftSuspension($user->id))->delay(Carbon::now()->addSeconds(60));
        $this->dispatch($job);
    }
}