<?php

namespace Cockpit\Tests\Unit\Context\Dump;

use Cockpit\Context\Dump\Dump;
use Cockpit\Tests\TestCase;
use Symfony\Component\VarDumper\VarDumper;

class DumpTest extends TestCase
{
    private function getHtmlString():string
    {
        return
            <<<'EOTXT'
        <foo></foo><bar>"<span class=sf-dump-str title="9 characters">Text dump</span>"
        </bar>
        EOTXT;
    }

    /** @test */
    public function it_should_mount_valid_dump_data(): void
    {
        $sourceFrame = [
            "file"     => "Cockpit\Tests\Unit\Context\Dump\VarDumper.php",
            "line"     => 123,
            "function" => "dump",
            "class"    => VarDumper::class,
            "type"     => "->"
        ];

        $dump = new Dump($this->getHtmlString(), $sourceFrame['file'], $sourceFrame['line'], 123);

        $this->assertSame([
            'html_dump'   => $this->getHtmlString(),
            'file'        => $sourceFrame['file'],
            'line_number' => $sourceFrame['line'],
            'microtime'   => 123.0
        ], $dump->toArray());
    }
}
