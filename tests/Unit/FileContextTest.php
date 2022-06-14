<?php

use Cockpit\FileContext;

it('should return an empty line if file doesn\'t exists', function () {
    $line    = 1;
    $content = FileContext::getContext('/an/invalid/path', $line);

    $this->assertEquals([
        $line => ''
    ], $content);
});

it('should return file lines if the file exists', function () {
    $content = FileContext::getContext(__DIR__ . '/../fixtures/example.php', 15);

    $this->assertEquals([
        6  => "",
        7  => '    public function __construct(int $qty)',
        8  => "    {",
        9  => '        $this->qty = $qty;',
        10 => "    }",
        11 => "",
        12 => "    public function execute()",
        13 => "    {",
        14 => '        if ($this->qty < 1) {',
        15 => "            throw new InvalidArgumentException('The quantity must be greater than 1');",
        16 => "        }",
        17 => "    }",
        18 => "}",
        19 => "",
    ], $content);
});

