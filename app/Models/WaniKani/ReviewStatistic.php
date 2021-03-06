<?php

namespace App\Models\WaniKani;

use Illuminate\Database\Eloquent\Model;

class ReviewStatistic extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    public function subject()
    {
        return $this->belongsTo('App\Models\WaniKani\Subject');
    }
}
