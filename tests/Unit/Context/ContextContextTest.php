<?php

namespace Cockpit\Tests\Unit\Context;

use Cockpit\Context\ContextContext;
use Cockpit\Tests\TestCase;
use Exception;
use Monolog\Logger;
use Monolog\LogRecord;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class ContextContextTest extends TestCase
{
    #[Test]
    #[DataProvider('data')]
    public function it_should_parse_the_extra_context($extra, $expected): void
    {
        $exception = new Exception();

        $record = new LogRecord(
            datetime: now()->toDateTimeImmutable(),
            channel: 'some-name',
            level: Logger::toMonologLevel(400),
            message: 'some-message',
            context: [
                'exception' => $exception,
            ],
            extra: $extra,
        );

        $contextContext = new ContextContext($record);

        $this->assertSame($expected, $contextContext->getContext());
    }

    public static function data(): array
    {
        return [
            [
                'extra' => [
                    'cockpit_context' => ['foo' => 'bar'],
                ],
                'expected' => ['foo' => 'bar'],
            ],
            [
                'extra' => [],
                'expected' => [],
            ],
        ];
    }
}
