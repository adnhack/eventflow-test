<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventLocation extends Model
{
    protected $fillable = [
        'event_id',
        'location',
    ];
}
