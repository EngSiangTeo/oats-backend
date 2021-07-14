<?php

namespace App\Modules\Chat\Transformers;

use League\Fractal\TransformerAbstract;
use App\Modules\Chat\Models\Message;

class MessageTransformer extends TransformerAbstract
{
    public function transform(Message $message)
    {
        $messageArray = [
            'username' => $message->user->name,
            'message' => $message->content,
        ];

        return $messageArray;
    }
}
