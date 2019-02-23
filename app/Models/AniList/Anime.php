<?php

namespace App\Models\AniList;

use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    protected $table = 'anime';

    protected $appends = ['score_factor'];

    public function getScoreFactorAttribute()
    {
        return $this->average_score + $this->my_score;
    }
}
