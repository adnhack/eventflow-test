<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventInformation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_informations';

    protected $fillable = [
        'event_id',
        'description',
        'additional_info',
    ];
}
