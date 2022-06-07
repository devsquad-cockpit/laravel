<?php

namespace Cockpit\Exceptions;

use Cockpit\Cockpit;
use Monolog\Handler\AbstractProcessingHandler;
use Throwable;

class Handler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        if (isset($record['context']['exception']) && $record['context']['exception'] instanceof Throwable) {
            Cockpit::handle($record['context']['exception']);
        }
    }
}
