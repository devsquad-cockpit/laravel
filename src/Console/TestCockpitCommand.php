<?php

namespace Cockpit\Console;

use Cockpit\Test\ErrorDefinition;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TestCockpitCommand extends Command
{
    protected $signature = 'cockpit:test';

    protected $description = 'Send fake data to webhook';

    public function handle(): int
    {
        $definition = (new ErrorDefinition())->definition();

        if (!config('cockpit.enabled')) {
            $this->info('You must set COCKPIT_ENABLED env to true');

            return 0;
        }

        if (!config('cockpit.route')) {
            $this->info('You must fill COCKPIT_ROUTE env with a valid cockpit endpoint');

            return 0;
        }

        Http::post(config('cockpit.route'), [
            'resolved_at' => null,
            'exception'   => $definition['exception'],
            'message'     => $definition['message'],
            'file'        => $definition['file'],
            'code'        => $definition['code'],
            'type'        => $definition['type'],
            'url'         => $definition['url'],
            'trace'       => $definition['trace'],
            'debug'       => $definition['debug'],
            'app'         => $definition['app'],
            'user'        => $definition['user'],
            'context'     => $definition['context'],
            'request'     => $definition['request'],
            'command'     => $definition['command'],
            'job'         => $definition['job'],
            'livewire'    => $definition['livewire'],
            'environment' => $definition['environment'],
        ]);

        $link = Str::of(config('cockpit.route'))->replace('webhook', '');

        $this->info("Everything works fine!\nCheckout at: $link");

        return 0;
    }
}
