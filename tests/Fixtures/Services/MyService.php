<?php

namespace Cockpit\Tests\Fixtures\Services;

use Cockpit\Tests\Fixtures\Exceptions\MyException;

class MyService
{
    public function handle(): void
    {
        throw new MyException();
    }
}
