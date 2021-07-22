<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Carbon\Carbon;
use App\Modules\Account\User\Models\User;
use App\Events\UserSuspended;
// use App\Modules\Chat\Jobs\LiftSuspension;
use Illuminate\Foundation\Bus\DispatchesJobs;

class StartSuspension 
{
    use DispatchesJobs;

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserSuspended $event)
    {
        sleep(7);
        $user = $event->user;
        $suspensionPeriod = Carbon::now()->addMinutes(10);
        $user->suspension_period = $suspensionPeriod;
        $user->save();
        // $job = new LiftSuspension($user->id);
        // $this->dispatch($job)->delay($suspensionPeriod);
    }
}
