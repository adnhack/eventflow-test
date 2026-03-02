<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\EventSchedule;

class SaveEventSchedule implements ShouldQueue
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
    private $event_id;

    /**
     * Create a new job instance.
     */
    public function __construct( $payload, $event_id )
    {
        $this->payload = $payload;
        $this->event_id = $event_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        EventSchedule::create( [
            'event_id' => $this->event_id,
            'date' => date('Y-m-d', strtotime($this->payload['date'])),
            'start_time' => $this->payload['start_time'],
            'end_time' => $this->payload['end_time']
        ]);
    }
}
