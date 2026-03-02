<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\SaveEnrichmentDataJob;
use App\Models\Event;
use App\Models\EventInformation;

class ProcessNewEventRequest implements ShouldQueue
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
    public function handle( $payload ): void
    {
        $event = new Event;
        $event->eventname = $payload['eventname'];
        $event->save();
        $event_id = $event->id;

        EventInformation::create( [
            'event_id' => $event_id,
            'description' => $payload['description'],
            'additional_info' => $payload['additional_info'] ?? ''
        ]);

        SaveEventLocation::dispatch( $payload, $event_id );
        SaveEventPrice::dispatch( $payload, $event_id );
        SaveEventSchedule::dispatch( $payload, $event_id );
        SaveEnrichmentDataJob::dispatch( $event_id );
    }
}
