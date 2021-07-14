<?php

namespace App\Modules\Listing\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $connection = 'mysql';
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

}