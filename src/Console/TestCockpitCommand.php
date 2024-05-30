<?php

namespace Cockpit\Console;

use Cockpit\Exceptions\CockpitErrorHandler;
use Exception;
use Illuminate\Console\Command;
use Monolog\DateTimeImmutable;
use Monolog\Level;
use Monolog\LogRecord;
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

        if (!config('cockpit.domain')) {
            $this->error('You must fill COCKPIT_DOMAIN env with a valid cockpit endpoint');

            return Status::FAILURE;
        }

        if (!config('logging.channels.cockpit.driver')) {
            $this->error('Cockpit logging config not found. Add it to config/logging.php');

            $sample = <<<CODE
'channels' => [
    // ...
    'cockpit' => [
        'driver' => 'cockpit',
    ],
],
CODE;

            $this->info($sample);

            return Status::FAILURE;
        }

        if (!in_array('cockpit', config('logging.channels.stack.channels'))) {
            $this->error('Cockpit logging config not found at stack channels. Fill environment LOG_STACK with "cockpit"');

            $code = <<<CODE
// ...
LOG_STACK=single,cockpit
// ...
CODE;

            $this->info($code);

            return Status::FAILURE;
        }

        /** @var CockpitErrorHandler $errorHandler */
        $errorHandler = app(CockpitErrorHandler::class);
        $errorHandler->handle(new LogRecord(
            datetime: new DateTimeImmutable(true),
            channel: 'cockpit',
            level: Level::Error,
            message: 'Test generated by the cockpit:test artisan command',
            context: [
                'exception' => new Exception('Test generated by the cockpit:test artisan command'),
            ]
        ));

        if ($errorHandler->failed() === true || $errorHandler->failed() === null) {
            $this->error('We couldn\'t reach Cockpit Server at ' . config('cockpit.domain'));
            $this->error($errorHandler->reason());

            return Status::FAILURE;
        }

        $this->info(
            "Cockpit reached successfully. We sent a test Exception that has been registered."
        );

        return Status::SUCCESS;
    }
}
