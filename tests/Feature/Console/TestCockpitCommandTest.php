<?php

namespace Cockpit\Tests\Feature\Console;

use Cockpit\Console\TestCockpitCommand;
use Cockpit\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command as Status;

class TestCockpitCommandTest extends TestCase
{
    /** @test */
    public function it_should_send_cockpit_test_command(): void
    {
        app()->config->set('cockpit.enabled', true);
        app()->config->set('cockpit.domain', 'http://app.test');
        app()->config->set('logging.channels.cockpit.driver', 'cockpit');
        app()->config->set('logging.channels.stack.channels', ['cockpit']);

        Http::fake([
            'http://app.test/webhook' => Http::response(null, 201, []),
        ]);

        $this->artisan(TestCockpitCommand::class)->expectsOutput(
            "Cockpit reached successfully. We sent a test Exception that has been registered."
        )->assertExitCode(Status::SUCCESS);
    }

    /** @test */
    public function it_should_notice_when_isnt_able_to_send_test_when_route_is_empty(): void
    {
        app()->config->set('cockpit.domain', '');

        $this->artisan(TestCockpitCommand::class)
            ->expectsOutput('You must fill COCKPIT_DOMAIN env with a valid cockpit endpoint')
            ->assertExitCode(Status::FAILURE);
    }

    /** @test */
    public function it_should_notice_when_isnt_able_to_send_test_when_enabled_is_false(): void
    {
        app()->config->set('cockpit.enabled', false);

        $this->artisan(TestCockpitCommand::class)
            ->expectsOutput('You must set COCKPIT_ENABLED env to true')
            ->assertExitCode(Status::FAILURE);
    }

    /** @test */
    public function it_should_return_an_error_message(): void
    {
        $wrongDomain = 'http://wrong-domain.test';

        app()->config->set('cockpit.enabled', true);
        app()->config->set('cockpit.domain', $wrongDomain);
        app()->config->set('logging.channels.cockpit.driver', 'cockpit');
        app()->config->set('logging.channels.stack.channels', ['cockpit']);

        Http::fake([
            "$wrongDomain/webhook" => Http::response(null, 404, []),
        ]);

        $this->artisan(TestCockpitCommand::class)
            ->expectsOutput("We couldn't reach Cockpit Server at $wrongDomain")
            ->expectsOutput("Reason: 404 Not Found")
            ->assertExitCode(Status::FAILURE);
    }

    /** @test */
    public function it_should_warn_when_cant_ensure_logging_config(): void
    {
        app()->config->set('cockpit.enabled', true);
        app()->config->set('cockpit.domain', 'some-domain');

        $this->artisan(TestCockpitCommand::class)
            ->expectsOutput('Cockpit logging config not found. Add it to config/logging.php');
    }

    /** @test */
    public function it_should_warn_when_cant_ensure_stack_logging_config(): void
    {
        app()->config->set('cockpit.enabled', true);
        app()->config->set('cockpit.domain', 'some-domain');
        app()->config->set('logging.channels.cockpit.driver', 'cockpit');

        $this->artisan(TestCockpitCommand::class)
            ->expectsOutput('Cockpit logging config not found at stack channels. Fill environment LOG_STACK with "cockpit"');
    }
}
