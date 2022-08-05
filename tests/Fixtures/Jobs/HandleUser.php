<?php

namespace Cockpit\Tests\Fixtures\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleUser implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use SerializesModels;
    use Queueable;

    public string $log;

    public function __construct(string $log)
    {
        $this->log = $log;
    }

    public function handle(): void
    {
        $this->fail(new Exception('This is an exception'));
    }
}
