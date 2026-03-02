<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

use App\Models\EventInformation;

class SaveEnrichmentDataJob implements ShouldQueue
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

    /**
     * The event id.
     *
     * @var int
     */
    private $event_id;

    /**
     * Create a new job instance.
     */
    public function __construct( $event_id )
    {
        $this->event_id = $event_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payload['enriched_data'] = Http::retry(5, 100)->post('http://eventflow.local/api/enriched/data', [
            'name' => 'system',
            'type' => 'getdata',
        ]);
        $event_information = EventInformation::find($this->event_id);
        $event_information->enriched_data = $event_information->enriched_data . $payload['enriched_data'];
        $event_information->save();
    }
}
