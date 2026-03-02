<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\EventInvalid;

class ProcessInvalidEventRequest implements ShouldQueue
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
    public function __construct( $payload, $validation_errors )
    {
        $this->payload = [
            'data' => $payload,
            'validation_errors' => $validation_errors
        ];
    }

    /**
     * Execute the job.
     */
    public function handle( $payload ): void
    {
        EventInvalid::create([
            'json_data' => json_encode($this->payload)
        ]);
    }
}
