<?php

namespace Cockpit\Tests\Unit\Context;

use Cockpit\Context\DumpContext;
use Cockpit\Tests\TestCase;
use Mockery;
use Symfony\Component\VarDumper\Cloner\VarCloner;

class DumpContextTest extends TestCase
{
    private function getHtmlString(): string
    {
        return
            <<<'EOTXT'
        <span class=sf-dump-str title="9 characters">Text dump</span>
        EOTXT;
    }

    private function getMockDumpContext(?array $data = null): DumpContext
    {
        return Mockery::mock(DumpContext::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('findSourceFrame')
            ->andReturn($data)
            ->getMock();
    }

    /** @test */
    public function it_should_dump_context_record_data_with_success_and_get_valid_context(): void
    {
        $expectedFile = [
            "file"     => "Teste.php",
            "line"     => 11,
            "function" => "{closure}",
            "class"    => "Illuminate\Routing\RouteFileRegistrar",
            "type"     => "->"
        ];

        $mock = $this->getMockDumpContext($expectedFile);
        $mock->record((new VarCloner())->cloneVar("Text dump"));
        $response = $mock->getContext()[0];

        $this->assertArrayHasKey('html_dump', $response);
        $this->assertArrayHasKey('file', $response);
        $this->assertArrayHasKey('line_number', $response);
        $this->assertArrayHasKey('microtime', $response);
    }

    /** @test */
    public function it_should_dump_context_record_data_with_empty_source_frame_return(): void
    {
        $mock = $this->getMockDumpContext();
        $mock->record((new VarCloner())->cloneVar("Text dump"));
        $response = $mock->getContext()[0];

        $this->assertIsArray($response);
        $this->assertIsString($response['html_dump']);
        $this->stringContains($response['html_dump'], $this->getHtmlString());
        $this->assertSame('', $response['file']);
        $this->assertSame(0, $response['line_number']);
    }

    /** @test */
    public function it_should_dump_context_created_with_empty_data(): void
    {
        $this->assertSame([], $this->app->make(DumpContext::class)->getContext());
    }

    /** @test */
    public function it_should_dump_context_reset_call_set_empty_data(): void
    {
        $dumpContext = $this->app->make(DumpContext::class);
        $dumpContext->record((new VarCloner())->cloneVar("Text dump"));

        $this->assertCount(1, $dumpContext->getContext());

        $dumpContext->reset();

        $this->assertSame([], $dumpContext->getContext());
    }
}