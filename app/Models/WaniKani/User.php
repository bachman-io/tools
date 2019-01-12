<?php

namespace App\Models\WaniKani;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}
