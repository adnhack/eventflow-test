<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\ProcessNewEventRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Event;
use App\Models\EventInformation;
use App\Models\EventLocation;
use App\Models\EventPrice;
use App\Models\EventSchedule;

use App\Models\EventRule;

class EventCreationDispatcher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $retryAfter = 60;

    private $payload;

    /**
     * Create a new job instance.
     */
    public function __construct( $payload )
     {
        $this->payload = $payload;
     }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $score = 0;
        $queue = 'low';
        $score_result = [];
        $searchable = [];
        $priority_rules = EventRule::get()->toArray();
        foreach ( $priority_rules as $rule ) {
            if ( 'event_name' == $rule['search_column'] ) {
                $searchable['Event'][] = [ $rule['search_column'] => $rule['value'] ];
            }
            if ( in_array($rule['search_column'], ['description', 'additional_info']) ) {
                $searchable['EventInformation'][] = [ $rule['search_column'] => $rule['value'] ];
            }
            if ( 'location' == $rule['search_column'] ) {
                $searchable['EventLocation'][] = [ $rule['search_column'] => $rule['value']];
            }
            if ( in_array($rule['search_column'], ['price', 'reservation', 'capacity']) ) {
                $searchable['EventPrice'][] = [ $rule['search_column'] => $rule['value']];
            }
            if ( in_array($rule['search_column'], ['event_date', 'start_time', 'end_time']) ) {
                $searchable['EventSchedule'][] = [ $rule['search_column'] => $rule['value']];
            }
        }
        
        foreach ( $searchable as $key => $value ) {
            $counter = 0;
            $query = DB::table($key);
            foreach ( $value as $column => $condition ) {
                if ( 0 == $counter ) {
                    $query->where($column, 'like', "%$condition%");
                } else {
                    $query->orWhere($column, 'like', "%$condition%");
                }
            }
            $score_result[] = $query->max('score');
        }

        $score = empty($score_result) ? 0 : max($score_result);
        $score = $score >= 70 ? 'high' : ( $score >= 30 ? 'medium' : 'low');
        ProcessNewEventRequest::dispatch( $this->payload, $score )->onQueue($score);
    }
}
