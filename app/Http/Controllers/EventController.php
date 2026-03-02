<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\DataNormalizerController;

use App\Jobs\EventCreationDispatcher;
use App\Jobs\ProcessNewEventRequest;
use App\Jobs\ProcessInvalidEventRequest;

class EventController extends Controller
{
    public function handler(Request $request, $source)
    {
        //php artisan queue:work --queue=high,low,default --sleep=3 --tries=3
        $payload = json_decode($request->getContent(), true);
        $validation_fail = self::validateFormat( $payload, $source );
        $normalized_data = DataNormalizerController::normalize( $payload['data'] );
        $normalized_data['validation'] = $validation_fail;

        if ( false === $validation_fail[0] ) {
            ProcessInvalidEventRequest::dispatch( $normalized_data, $validation_fail[1] );
        } else {
            EventCreationDispatcher::dispatch( $normalized_data );
        }

        // Return a response to acknowledge receipt of the webhook
        return response()->json([
            'message' => 'Webhook received successfully']
        , 200);
    }

    private static function validateFormat( $payload, $source ) : array
    {
        switch( $source ) {
            case 'globalevents':

                $rule = [
                    "event" => 'required','array',
                    'event.eventname' => 'required','string',
                    'event.location' => 'required','string',
                    'event.description' => 'required','string',
                    "schedule" => 'required','array',
                    'schedule.date' => 'required','date',
                    'schedule.start_time' => 'required','date_format:H:i',
                    'schedule.end_time' => 'required','date_format:H:i',
                    "prices" => 'required','array',
                    'prices.reservation' => 'required','integer',
                    "information" => 'required','array',
                ];
                break;
            case 'eventsmani':
                $rule = [
                    "event" => 'required','array',
                    'event.eventname' => 'required','string',
                    'event.location' => 'required','string',
                    'event.date' => 'required','date',
                    'event.start_time' => 'required','date_format:H:i',
                    'event.end_time' => 'required','date_format:H:i',
                    "tickets" => 'required','array',
                    'tickets.reservation' => 'required','integer',
                    "information" => 'required','array',
                    'information.description' => 'required','string',
                ];
                break;
            case 'fastevents':
                $rule = [
                    "event" => 'required','array',
                    'event.eventname' => 'required','string',
                    'event.date' => 'required','date',
                    'event.start_time' => 'required','date_format:H:i',
                    'event.end_time' => 'required','date_format:H:i',
                    'event.location' => 'required','string',
                    'event.description' => 'required','string',
                    'event.reservation' => 'required','integer',
                ];
                break;
            default:
                return [true, ['Unsupported source']];
        }
        try{
            $validation_result = Validator::make( $payload['data'], $rule );
        } catch( \Illuminate\Validation\ValidationException $e ) {
            return [true, $e->errors()];
        }
        return [
            $validation_result->fails(),
            $validation_result->errors()->messages()
        ];
    }
}
