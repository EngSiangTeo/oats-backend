<?php

namespace App\Http\Controllers\Api\v1\Listing;

use Auth;
use App\Modules\Listing\Models\Listing;
use App\Modules\Account\User\Models\User;
use App\Http\Controllers\Api\ApiController;
use App\Modules\Chat\Models\ChatParticipant;
use App\Modules\Listing\Transformers\ListingTransformer;

/**
* @group Chat endpoints
*/
class ListingApiController extends ApiController
{
    // returns all listings posted by user
    public function index()
    {
        $user = Auth::user();

        $listings = Listing::with("user")->get();

        $listings = Fractal::create()
                    ->collection($listings)
                    ->transformWith(new ListingTransformer($user->id))
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

        return $this->respondSuccess($listings, trans('api.generic.index.success', ['resource' => 'Messages']));
    }

    // public function createNewConversation(Request $request)
    // {
    //     $creator = Auth::user();

    //     $chat = Chat::Create([
    //         'creator_id' => $creator->id,
    //         'listing_id' => $request->input('listing_id'),
    //     ]);

    //     $userChat = ChatParticipant::Create([
    //         'user_id' => $creator->id,
    //         'chat_id' => $chat->id,
    //     ]);

    //     $targetChat = ChatParticipant::Create([
    //         'user_id' => $request->input('target_id'),
    //         'chat_id' => $chat->id,
    //     ]);

    //     return $this->respondSuccess($chat, trans('success', ['resource' => 'Chat']));
    // }
}
