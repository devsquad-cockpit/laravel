<?php

namespace Cockpit\Tests\Unit\Context\Dump;

use Cockpit\Context\Dump\MultiDumpHandler;

it('should be add multiple callable function at multidump handler and execute all functions', function () {
    $multiDumpHandler = new MultiDumpHandler;
    $multiDumpHandler->addHandler(fn ($var) => var_dump('call one ' . $var));
    $multiDumpHandler->addHandler(fn ($var) => var_dump('call two ' . $var));

    expect($multiDumpHandler->handlers)->toBeArray()->toHaveCount(2);

    expect($multiDumpHandler->handlers[0])->toBeCallable();

    ob_start();
    $multiDumpHandler->dump("Dump to test");

    expect(ob_get_clean())
        ->toContain('string(21) "call one Dump to test"')
        ->toContain('string(21) "call two Dump to test"');
});
