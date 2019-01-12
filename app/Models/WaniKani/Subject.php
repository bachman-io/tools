<?php

namespace App\Models\WaniKani;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    public function assignment()
    {
        return $this->hasOne('App\Models\WaniKani\Assignment');
    }

    public function review_statistic()
    {
        return $this->hasOne('App\Models\WaniKani\ReviewStatistic');
    }
}
