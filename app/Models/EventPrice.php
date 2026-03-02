<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPrice extends Model
{
    protected $fillable = [
        'event_id',
        'price',
        'reservation',
        'capacity',
    ];
}
