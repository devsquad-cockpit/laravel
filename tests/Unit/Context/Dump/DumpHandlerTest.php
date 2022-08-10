<?php

namespace Cockpit\Tests\Unit\Context\Dump;

use Cockpit\Context\Dump\DumpHandler;
use Cockpit\Context\DumpContext;

it('should be execute dump handler record value at dump context', function () {
    $value       = "Text dump";
    $dumpContext = $this->app->make(DumpContext::class);

expect($dumpContext->getContext())
    ->toBeEmpty()
    ->toBeArray()
    ->toHaveCount(0);

    $dumpHandler = new DumpHandler($dumpContext);
    $dumpHandler->dump($value);

    $response = $dumpContext->getContext()[0];

    expect($response)
        ->toBeArray()
        ->toHaveKeys(['html_dump', 'file', 'line_number', 'microtime']
        ->and($response['html_dump'])
        ->toBeString()
        ->toContain($value);
});
