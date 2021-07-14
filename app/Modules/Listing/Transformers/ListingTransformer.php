<?php

namespace App\Modules\Listing\Transformers;

use League\Fractal\TransformerAbstract;
use App\Modules\Listing\Models\Listing;

class ListingTransformer extends TransformerAbstract
{
	protected $currentUserId;

	public function __construct($currentUserId)
	{
		$this->currentUserId = $currentUserId;
	}

    public function transform(Listing $listing)
    {
    	$userId = $this->currentUserId;

        $listingArray = [
			'listing_id'=> $listing->id,
            'user_id' => $listing->user_id,
            'title' => $listing->title,
            'description' => $listing->description,
            'category' => $listing->category,
            'price' => $listing->price,
            'listed_date' => $listing->listed_date,
            'deprioritized' => $listing->deprioritized,
            'created_at' => $listing->created_at,
            'updated_at' => $listing->updated_at
        ];

        return $listingArray;
    }
}
