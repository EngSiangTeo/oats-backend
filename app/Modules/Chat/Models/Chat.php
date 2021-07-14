<?php

namespace App\Modules\Chat\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Account\User\Models\User;

class Chat extends Model
{
    protected $fillable = [
        'creator_id',
        'listing_id'
    ];

    public function chatParticipant() {
        return $this->hasMany(ChatParticipant::class, 'chat_id');
    }

    public function messages() {
        return $this->hasMany(Message::class, 'chat_id');
    }
}