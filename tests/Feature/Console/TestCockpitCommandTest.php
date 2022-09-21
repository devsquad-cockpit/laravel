<?php

use Cockpit\Console\TestCockpitCommand;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command as Status;

it('should send cockpit:test command', function () {
    app()->config->set('cockpit.enabled', true);
    app()->config->set('cockpit.route', 'http://app.test/webhook');

    Http::fake([
        'http://app.test/webhook' => Http::response(null, 201, []),
    ]);

    $this->artisan(TestCockpitCommand::class)->expectsOutput(
        "Cockpit reached successfully. We sent a test Exception that has been registered."
    )->assertExitCode(Status::SUCCESS);
});

it('should notice when isnt able to send test when route is empty', function () {
    app()->config->set('cockpit.route', '');

    $this->artisan(TestCockpitCommand::class)
        ->expectsOutput('You must fill COCKPIT_ROUTE env with a valid cockpit endpoint')
        ->assertExitCode(Status::FAILURE);
});

it('should notice when isnt able to send test when enabled is false', function () {
    app()->config->set('cockpit.enabled', false);

    $this->artisan(TestCockpitCommand::class)
        ->expectsOutput('You must set COCKPIT_ENABLED env to true')
        ->assertExitCode(Status::FAILURE);
});

it('can\'t reach server by 404 not found', function () {
    app()->config->set('cockpit.enabled', true);
    app()->config->set('cockpit.route', 'http://app.test/wrong-url');

    Http::fake([
        'http://app.test/wrong-url' => Http::response(null, 404, []),
    ]);

    $this->artisan(TestCockpitCommand::class)
        ->expectsOutput("We couldn't reach Cockpit Server at http://app.test/wrong-url")
        ->expectsOutput("Reason: 404 Not Found")
        ->assertExitCode(Status::FAILURE);
});
