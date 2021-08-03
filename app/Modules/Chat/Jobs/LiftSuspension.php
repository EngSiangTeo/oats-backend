<?php

namespace App\Modules\Chat\Jobs;

use App\Jobs\BroadcastBan;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Modules\Account\User\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LiftSuspension implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $userId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::where('id', $this->userId)
                    ->update([
                        'suspension_period'=>null,
                        'caroupoint'=>81
                    ]);

        $user = User::where('id', $this->userId)
                    ->first();
        BroadcastBan::dispatch($user);

        var_dump('a');
    }
}