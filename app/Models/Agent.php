<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Agent extends Authenticatable
{
   use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'wallet_balance',
        'withdrawal_allowed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
