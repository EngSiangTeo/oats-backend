<?php

namespace App\Http\Controllers\Api\v1\Chat;

use Auth;
use Exception;
use App\Events\MessageSent;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Modules\Chat\Models\Chat;
use App\Modules\Chat\Models\ChatParticipant;
use Spatie\Fractalistic\ArraySerializer;
use App\Modules\Account\User\Models\User;
use App\Http\Controllers\Api\ApiController;
use App\Modules\Chat\Transformers\ChatTransformer;

/**
* @group Chat endpoints
*/
class ChatController extends ApiController
{
    public function index()
    {
        $user = Auth::user();

        $chats = Chat::with("chatParticipant.user")
                        ->whereHas("chatParticipant", function ($query) use ($user) {
                            return $query->where('user_id', '=', $user->id);
                        })
                        ->get();

        $chats = Fractal::create()
                    ->collection($chats)
                    ->transformWith(new ChatTransformer($user->id))
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

        return $this->respondSuccess($chats, trans('api.generic.index.success', ['resource' => 'Messages']));
    }

    public function createNewConversation(Request $request)
    {
        $creator = Auth::user();

        $chat = Chat::Create([
            'creator_id' => $creator->id,
            'listing_id' => $request->input('listing_id'),
        ]);

        $userChat = ChatParticipant::Create([
            'user_id' => $creator->id,
            'chat_id' => $chat->id,
        ]);

        $targetChat = ChatParticipant::Create([
            'user_id' => $request->input('target_id'),
            'chat_id' => $chat->id,
        ]);

        return $this->respondSuccess($chat, trans('success', ['resource' => 'Chat']));
    }
}
