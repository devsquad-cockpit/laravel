<?php

namespace Cockpit\Tests\Unit\Context;

use Cockpit\Context\StackTraceContext;
use Cockpit\Tests\Fixtures\Services\MyService;
use Cockpit\Tests\TestCase;

class StackTraceContextTest extends TestCase
{
    /** @test */
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
            'line'              => 17,
            'function'          => 'it_should_get_stack_trace_from_a_exception',
            'class'             => 'Cockpit\Tests\Unit\Context\StackTraceContextTest',
            'application_frame' => true,
            'preview'           => [
                7  => 'use Cockpit\Tests\TestCase;',
                8  => '',
                9  => 'class StackTraceContextTest extends TestCase',
                10 => '{',
                11 => '    /** @test */',
                12 => '    public function it_should_get_stack_trace_from_a_exception(): void',
                13 => '    {',
                14 => '        $exception = null;',
                15 => '',
                16 => '        try {',
                17 => '            (new MyService())->handle();',
                18 => '        } catch (\Exception $e) {',
                19 => '            $exception = $e;',
                20 => '        }',
                21 => '',
                22 => '        $context = (new StackTraceContext(app(), $exception))->getContext();',
                23 => '',
                24 => '        $this->assertIsArray($context);',
                25 => '        $this->assertSame([',
                26 => '            \'file\'              => str_replace(\'/Unit/Context\', \'\', __DIR__) . \'/Fixtures/Services/MyService.php\',',
            ],
        ], $context[1]);
    }
}
