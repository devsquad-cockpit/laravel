<?php

namespace Cockpit\Tests\Unit\Context\Dump;

use Cockpit\Context\Dump\MultiDumpHandler;
use Cockpit\Tests\TestCase;


class MultiDumpHandlerTest extends TestCase
{
    /** @test */
    public function it_should_be_add_multiple_callable_function_at_multidump_handler_and_execute_all_functions(): void
    {
        $multiDumpHandler = new MultiDumpHandler;
        $multiDumpHandler->addHandler(function ($var) {
            var_dump('call one ' . $var);
        });

        $multiDumpHandler->addHandler(function ($var) {
            var_dump('call two ' . $var);
        });

        $this->assertIsArray($multiDumpHandler->getHandlers());
        $this->assertCount(2, $multiDumpHandler->getHandlers());
        $this->assertIsCallable($multiDumpHandler->getHandlers()[0]);

        ob_start();

        $multiDumpHandler->dump("Dump to test");

        $content = ob_get_clean();

        $this->assertStringContainsString('string(21) "call one Dump to test"', $content);
        $this->assertStringContainsString('string(21) "call one Dump to test"', $content);
    }
}
