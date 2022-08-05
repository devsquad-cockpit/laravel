<?php

namespace Cockpit\Tests\Fixtures\Execptions;

use Exception;

class ExceptionTest extends Exception
{
    protected $message = 'This is an exception message';
}
