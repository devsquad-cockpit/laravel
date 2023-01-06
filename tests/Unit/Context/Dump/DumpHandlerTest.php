<?php

namespace Cockpit\Tests\Unit\Context\Dump;

use Cockpit\Context\Dump\DumpHandler;
use Cockpit\Context\DumpContext;
use Cockpit\Tests\TestCase;

class DumpHandlerTest extends TestCase
{
    /** @test */
    public function it_should_be_execute_dump_handler_record_value_at_dump_context(): void
    {
        $value       = "Text dump";
        $dumpContext = $this->app->make(DumpContext::class);

        $this->assertSame([], $dumpContext->getContext());

        $dumpHandler = new DumpHandler($dumpContext);
        $dumpHandler->dump($value);

        $response = $dumpContext->getContext()[0];

        $this->assertArrayHasKey('html_dump', $response);
        $this->assertArrayHasKey('file', $response);
        $this->assertArrayHasKey('line_number', $response);
        $this->assertArrayHasKey('microtime', $response);
        $this->assertStringContainsString($value, $response['html_dump']);
    }
}

