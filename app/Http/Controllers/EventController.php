<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function handler(Request $request, $source)
    {
        echo "hola mundop";
        exit;
        
        $data = $request->all();
        $global = [
            'event' => [
                'name' => '',
                'location' => '',
                'description' => '',
            ],
            'schedule' => [
                'date' => '',
                'start_time' => '',
                'end_time' => '',
            ],
            'prices' => [
                'reservation' => '',
                'price' => '',
            ],
            'information' => [
                'capacity' => '',
                'additional_info' => '',
            ]
        ];

        $event_mania = [
            'event' => [
                'name' => '',
                'location' => '',
                'date' => '',
                'start_time' => '',
                'end_time' => '',
            ],
            'tickets' => [
                'reservation' => '',
                'price' => '',
                'capacity' => '',
            ],
            'information' => [
                'description' => '',
                'additional_info' => '',
            ]
        ];

        $fast_event = [
            'event' => [
                'name' => '',
                'date' => '',
                'start_time' => '',
                'end_time' => '',
                'location' => '',
                'description' => '',
                'reservation' => '',
                'price' => '',
            ],
        ];
        
        var_dump($data);
        echo json_encode($global);
        echo json_encode($event_mania);
        echo json_encode($fast_event);
        exit;

        $is_valid = self::validateFormat( [ 'data' => $data, 'source' => $source ] );

        if ( !$is_valid ) {
            return response()->json(['message' => 'Invalid / Unsupported webhook format'], 400);
        }

        // Return a response to acknowledge receipt of the webhook
        return response()->json(['message' => 'Webhook received successfully'], 200);
    }

    private static function validateFormat( $payload ) : bool
    {
        switch( $payload['source'] ) {
            case 'globalevents':
                
                break;
            case 'eventsmani':
                
                break;
            case 'fastevents':
                
                break;
            default:
                return false; // Unsupported source
        }
        return true;
    }

    private static function formatData( $payload ) : array
    {
        return [];
    }
}
