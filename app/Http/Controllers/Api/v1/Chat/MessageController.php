<?php

namespace App\Http\Controllers\Api\v1\Chat;

use Auth;
use Exception;
use App\Events\MessageSent;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Modules\Chat\Models\Chat;
use Spatie\Fractalistic\ArraySerializer;
use App\Modules\Account\User\Models\User;
use App\Http\Controllers\Api\ApiController;
use App\Modules\Chat\Transformers\MessageTransformer;
use App\Modules\Chat\Transformers\ChatMessagesTransformer;

/**
* @group Chat endpoints
*/
class MessageController extends ApiController
{
    public function index()
    {
        $messages = Chat::with('message.user')
                        ->get();

        // $messages = Fractal::create()
        //             ->collection($messages)
        //             ->transformWith(new MessageTransformer())
        //             ->serializeWith(new ArraySerializer())
        //             ->toArray();

        return $this->respondSuccess($messages, trans('api.generic.index.success', ['resource' => 'Messages']));
    }

    public function messagesByChatId($chatId)
    {
        $user = Auth::user();

        $messages = Chat::findOrFail($chatId)
                        ->with('message.user');

        $messages = $messages->where('id', $chatId)
                                ->first();

        $messages = Fractal($messages, new ChatMessagesTransformer($user->id))
                        ->serializeWith(new ArraySerializer())
                        ->toArray();

        return $this->respondSuccess($messages, trans('api.generic.index.success', ['resource' => 'Messages']));
    }


    public function store($chatId, Request $request)
    {
        $user = Auth::user();

        $message = $user->messages()->create([
            'chat_id' => $chatId,
            'content' => $request->input('message')
        ]);

        broadcast(new MessageSent($user, $message))->toOthers();

        $message = Fractal($message, new MessageTransformer())->toArray();

        return $this->respondSuccess($message, trans('success', ['resource' => 'Messages']));
    }
}
