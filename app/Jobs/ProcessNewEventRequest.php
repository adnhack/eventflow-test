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
use Illuminate\Support\Facades\Log;

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
    public $score;

    /**
     * Create a new job instance.
     */
    public function __construct( $payload, $score )
    {
        $this->payload = $payload;
        $this->score = $score;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Job started.');

        $event = new Event;
        $event->eventname = $this->payload['eventname'];
        $event->save();
        $event_id = $event->event_id;

        EventInformation::create( [
            'event_id' => $event_id,
            'description' => $this->payload['description'],
            'additional_info' => $this->payload['additional_info'] ?? ''
        ]);

        SaveEventLocation::dispatch( $this->payload, $event_id )->onQueue($this->score);
        SaveEventPrice::dispatch( $this->payload, $event_id )->onQueue($this->score);
        SaveEventSchedule::dispatch( $this->payload, $event_id )->onQueue($this->score);
        SaveEnrichmentDataJob::dispatch( $event_id )->onQueue($this->score);
    }
}
