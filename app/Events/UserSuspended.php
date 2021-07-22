<?php

namespace App\Events;

use App\Modules\Account\User\Models\User;

class UserSuspended 
{
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