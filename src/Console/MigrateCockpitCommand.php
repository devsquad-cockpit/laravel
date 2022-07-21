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

    public function handle(): void
    {
        $arguments = [
            '--database' => 'cockpit',
            '--path'     => 'database/migrations/cockpit',
        ];

        $this->call('migrate', $arguments);
    }
}
