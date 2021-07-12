<?php

namespace App\Http\Controllers\Api\v1\Chat;

use Auth;
use Exception;
use App\Events\MessageSent;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Modules\Chat\Models\Message;
use App\Modules\Account\User\Models\User;
use App\Http\Controllers\Api\ApiController;
use App\Modules\Chat\Transformers\MessageTransformer;

/**
* @group Account endpoints
*/
class MessageController extends ApiController
{
    public function index()
    {
        return Message::with('user')->get();
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $message = $user->messages()->create([
            'message' => $request->input('message')
        ]);

        broadcast(new MessageSent($user, $message))->toOthers();

        $message = Fractal($message, new MessageTransformer())->toArray();

        return $this->respondSuccess($message, trans('success', ['resource' => 'Favourites']));
    }
}
