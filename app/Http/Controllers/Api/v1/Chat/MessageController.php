<?php

namespace App\Http\Controllers\Api\v1\Chat;

use Auth;
use Exception;
use App\Events\MessageSent;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use GuzzleHttp\RequestOptions;
use App\Modules\Chat\Models\Chat;
use Spatie\Fractalistic\ArraySerializer;
use App\Modules\Account\User\Models\User;
use GuzzleHttp\Client as GuzzleHttpClient;
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

        return $this->respondSuccess($messages, trans('api.generic.index.success', ['resource' => 'Messages']));
    }

    public function messagesByChatId($chatId)
    {
        $user = Auth::user();

        $messages = Chat::findOrFail($chatId)
                        ->with('message.user','chatParticipant', 'listing', 'listing.user');

        $messages = $messages->where('id', $chatId)
                                ->first();
        
        $participants = $messages->chatParticipant->pluck('user_id')->toArray();

        if (!in_array($user->id, $participants)) {
            return $this->respondUnauthorized();
        }

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

        $chatListing = Chat::findOrFail($chatId)
                        ->with('listing');
        $chatListing = $chatListing->where('id', $chatId)
                            ->first();

        $seller = $chatListing->listing->user_id;
        if ($seller !== $user->id) {
            $payload = [
             'text' => $request->input('message')
            ];

            $client = new GuzzleHttpClient;
            $res = $client->post('https://m0yvj161p3.execute-api.us-east-1.amazonaws.com/oats-staging/checkifoffer', [
                RequestOptions::JSON => $payload
            ]);

            $body = json_decode($res->getBody()->getContents()); 

            if ($body->statusCode == 200) {
                if ($body->body->quantity and $body->body->offer <= ($chatListing->listing->price * 0.8)) {
                    $message->system_if_offer = 1;
                }else {
                    $message->system_if_offer = 0;
                }
                $message->save();
            } else {
                return $this->respondError('System Error',500);
            }
        }

        $message = $message->fresh();

        broadcast(new MessageSent($user, $message))->toOthers();

        $message = Fractal($message, new MessageTransformer($user->id))->toArray();

        return $this->respondSuccess($message, trans('success', ['resource' => 'Messages']));
    }

}
