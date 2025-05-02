<?php

namespace Cockpit\Tests\Unit\helpers;

use Cockpit\Tests\TestCase;
use Exception;
use Illuminate\Log\Context\Repository as ContextRepository;
use Illuminate\Support\Facades\Context;
use PHPUnit\Framework\Attributes\Test;
use function Cockpit\report as report;

class HelperTest extends TestCase
{
    #[Test]
    public function test_if_can_send_the_error_with_context_data(): void
    {
        $context = ['foo' => 'bar'];

        Context::spy();

        $this->mock(ContextRepository::class, function ($mock) use ($context) {
            $mock->shouldReceive('all')->andReturn($context);
        });

        report(new Exception(), $context);

        Context::shouldHaveReceived('add', ['cockpit_context', $context]);
    }

    #[Test]
    public function test_if_can_send_the_error_without_context_data(): void
    {
        $context = [];

        Context::spy();

        $this->mock(ContextRepository::class, function ($mock) use ($context) {
            $mock->shouldReceive('all')->andReturn($context);
        });

        report(new Exception(), $context);

        Context::shouldNotHaveReceived('add', ['cockpit_context', $context]);
    }
}
