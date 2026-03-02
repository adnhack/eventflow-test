<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRule extends Model
{
    protected $fillable = [
        'search_value',
        'search_column',
    ];
}
