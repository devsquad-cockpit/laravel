<?php

namespace Cockpit\Tests\Unit\Context;

use Cockpit\Context\StackTraceContext;
use Cockpit\Tests\Fixtures\Services\MyService;
use Cockpit\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class StackTraceContextTest extends TestCase
{
    #[Test]
    public function it_should_get_stack_trace_from_a_exception(): void
    {
        $exception = null;

        try {
            (new MyService())->handle();
        } catch (\Exception $e) {
            $exception = $e;
        }

        $context = (new StackTraceContext(app(), $exception))->getContext();

        $this->assertIsArray($context);
        $this->assertSame([
            'file'              => str_replace('/Unit/Context', '', __DIR__) . '/Fixtures/Services/MyService.php',
            'line'              => 11,
            'function'          => 'handle',
            'class'             => MyService::class,
            'application_frame' => true,
            'preview'           => [
                1  => '<?php',
                2  => '',
                3  => 'namespace Cockpit\Tests\Fixtures\Services;',
                4  => '',
                5  => 'use Cockpit\Tests\Fixtures\Exceptions\MyException;',
                6  => '',
                7  => 'class MyService',
                8  => '{',
                9  => '    public function handle(): void',
                10 => '    {',
                11 => '        throw new MyException();',
                12 => '    }',
                13 => '}',
                14 => '',
            ],
        ], $context[0]);
        $this->assertSame([
            'file'              => __DIR__ . '/StackTraceContextTest.php',
            'line'              => 18,
            'function'          => 'it_should_get_stack_trace_from_a_exception',
            'class'             => 'Cockpit\Tests\Unit\Context\StackTraceContextTest',
            'application_frame' => true,
            'preview'           => [
                8 => 'use PHPUnit\Framework\Attributes\Test;',
                9  => '',
                10  => 'class StackTraceContextTest extends TestCase',
                11 => '{',
                12 => '    #[Test]',
                13 => '    public function it_should_get_stack_trace_from_a_exception(): void',
                14 => '    {',
                15 => '        $exception = null;',
                16 => '',
                17 => '        try {',
                18 => '            (new MyService())->handle();',
                19 => '        } catch (\Exception $e) {',
                20 => '            $exception = $e;',
                21 => '        }',
                22 => '',
                23 => '        $context = (new StackTraceContext(app(), $exception))->getContext();',
                24 => '',
                25 => '        $this->assertIsArray($context);',
                26 => '        $this->assertSame([',
                27 => '            \'file\'              => str_replace(\'/Unit/Context\', \'\', __DIR__) . \'/Fixtures/Services/MyService.php\',',
            ],
        ], $context[1]);
    }
}
