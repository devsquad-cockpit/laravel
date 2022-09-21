<?php

namespace Cockpit\Tests\Unit\Context;

use Cockpit\Context\CommandContext;
use Cockpit\Tests\TestCase;
use Illuminate\Foundation\Application;
use Mockery\MockInterface;

class CommandContextTest extends TestCase
{
    /** @test */
    public function it_should_return_a_context_data_when_executing_a_command(): void
    {
        $_SERVER['argv'] = [
            'php artisan',
            'fake:command'
        ];

        $context = new CommandContext(app());

        $this->assertSame([
            'command'   => 'php artisan fake:command',
            'arguments' => [],
        ], $context->getContext());
    }

    /** @test */
    public function it_should_return_a_context_data_when_executing_a_command_with_arguments(): void
    {
        $_SERVER['argv'] = [
            'php artisan',
            'fake:command',
            '--user=1',
        ];

        $context = new CommandContext(app());

        $this->assertSame([
            'command'   => 'php artisan fake:command',
            'arguments' => [
                '--user=1'
            ],
        ], $context->getContext());
    }

    /** @test */
    public function it_should_return_an_empty_array_if_application_is_not_running_in_console(): void
    {
        $app = $this->partialMock(Application::class, function (MockInterface $mock) {
            $mock->shouldReceive('runningInConsole')->andReturn(false);
        });

        $_SERVER['argv'] = [
            'php artisan',
            'fake:command',
            '--user=1',
        ];

        $context = new CommandContext($app);

        $this->assertSame([], $context->getContext());
    }

    /** @test */
    public function it_should_return_return_an_empty_array_if_arguments_are_empty(): void
    {
        $_SERVER['argv'] = [];

        $context = new CommandContext(app());

        $this->assertSame([], $context->getContext());
    }
}
