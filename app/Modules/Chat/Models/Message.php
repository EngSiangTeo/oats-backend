<?php

namespace App\Modules\Chat\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Account\User\Models\User;

class Message extends Model
{
    protected $fillable = [
        'message'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id', 'sender_id');
    }

    public function chat() {
        return $this->belongsTo(Chat::class, 'id', 'chat_id');
    }
}