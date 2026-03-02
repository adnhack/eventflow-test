<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataNormalizerController extends Controller
{
    const ARRAY_ELEMENTS = [
        'eventname',
        'location',
        'description',
        'date',
        'start_time',
        'end_time',
        'reservation',
        'price',
        'capacity',
        'additional_info',
    ];

    public static function normalize( $payload ) : array
    {
        $result = [];
        array_walk_recursive( $payload, function( $item, $key ) use ( &$result ) {
            if ( in_array( $key, self::ARRAY_ELEMENTS ) ) {
                $result[$key] = $item;
            }
        });
        return $result;
    }
}
