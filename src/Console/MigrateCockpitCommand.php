<?php

namespace Cockpit\Console;

use Illuminate\Console\Command;

class MigrateCockpitCommand extends Command
{
    protected $signature = 'cockpit:migrate
        {--F|force : Forces cockpit database migration}
        {--R|refresh : Forces cockpit database to be refreshed}
    ';

    protected $description = 'This command helps you to run cockpit migrations easily';

    public function handle(): int
    {
        $command   = 'migrate';
        $arguments = [
            '--database' => 'cockpit',
            '--path'     => 'database/migrations/cockpit',
        ];

        if ($this->option('force')) {
            $arguments['--force'] = true;
        }

        if ($this->option('refresh')) {
            if (!$this->confirm('This operation will wipe out all cockpit data. Do you want to continue?')) {
                $this->warn('The operation has been cancelled.');
                return self::FAILURE;
            }

            $command .= ':fresh';
        }

        $this->call($command, $arguments);

        $this->info('Cockpit database has been migrated!');

        return self::SUCCESS;
    }
}
