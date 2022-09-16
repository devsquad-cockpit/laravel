<?php

namespace Cockpit\Console;

use Cockpit\Exceptions\CockpitErrorHandler;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command as Status;

class TestCockpitCommand extends Command
{
    protected $signature = 'cockpit:test';

    protected $description = 'Send fake data to webhook';

    public function handle(): int
    {
        if (!config('cockpit.enabled')) {
            $this->error('You must set COCKPIT_ENABLED env to true');

            return Status::FAILURE;
        }

        if (!config('cockpit.route')) {
            $this->error('You must fill COCKPIT_ROUTE env with a valid cockpit endpoint');

            return Status::FAILURE;
        }

        /** @var CockpitErrorHandler $errorHandler */
        $errorHandler = app(CockpitErrorHandler::class);
        $errorHandler->write([
            'level'   => Logger::ERROR,
            'context' => [
                'exception' => new Exception('Some exception message'),
            ],
        ]);

        $link = Str::of(config('cockpit.route'))->replace('webhook', '');

        if ($errorHandler->failed() === true || $errorHandler->failed() === null) {
            $this->error('We couldn\'t reach Cockpit Server at ' . $link);
            $this->error($errorHandler->reason());

            return Status::FAILURE;
        }

        $this->info(
            "We could reach Cockpit Server. By the way, we send an example of exception, don't worry it's only a fake one. Checkout at: $link"
        );

        return Status::SUCCESS;
    }
}
