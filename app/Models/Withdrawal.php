<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'agent_id',
        'amount',
        'status',
        'processed_by',
        'processed_at',
    ];
}
