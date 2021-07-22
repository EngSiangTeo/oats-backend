<?php

namespace App\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Modules\Account\User\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserSuspended implements ShouldQueue
{
    use Queueable, Dispatchable;

    public $user;
    /**
     * Create a new event instance.
     *
     * @param  User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
} 