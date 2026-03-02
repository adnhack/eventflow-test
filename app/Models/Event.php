<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // Custom primary key column
    protected $primaryKey = 'event_id';
    protected $fillable = [
        'event_id',
    ];
}
