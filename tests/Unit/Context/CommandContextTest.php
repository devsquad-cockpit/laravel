<?php

namespace Cockpit\Tests\Unit\Context;

use Cockpit\Context\CommandContext;
use Illuminate\Foundation\Application;
use Mockery\MockInterface;

it('should return a context data when executing a command', function () {
    $_SERVER['argv'] = [
        'php artisan',
        'fake:command'
    ];

    $context = new CommandContext(app());

    expect($context->getContext())
        ->toBe([
            'command'   => 'php artisan fake:command',
            'arguments' => [],
        ]);
});

it('should return a context data when executing a command with arguments', function () {
    $_SERVER['argv'] = [
        'php artisan',
        'fake:command',
        '--user=1',
    ];

    $context = new CommandContext(app());

    expect($context->getContext())
        ->toBe([
            'command'   => 'php artisan fake:command',
            'arguments' => [
                '--user=1'
            ],
        ]);
});

it('should return an empty array if application is not running in console', function () {
    $app = $this->partialMock(Application::class, function (MockInterface $mock) {
        $mock->shouldReceive('runningInConsole')->andReturn(false);
    });

    $_SERVER['argv'] = [
        'php artisan',
        'fake:command',
        '--user=1',
    ];

    $context = new CommandContext($app);

    expect($context->getContext())
        ->toBeArray()
        ->toBeEmpty();
});
