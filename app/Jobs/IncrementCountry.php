<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class IncrementCountry implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly string $countryCode, private readonly int $value = 1)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Redis::hIncrBy('countries', $this->countryCode, $this->value);
    }
}
