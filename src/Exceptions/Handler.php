<?php

namespace Cockpit\Exceptions;

use Cockpit\Cockpit;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\FormattedRecord;
use Monolog\Logger;

class Handler extends AbstractProcessingHandler
{
    protected Cockpit $cockpit;

    public function __construct(Cockpit $cockpit, int $level = Logger::ERROR, bool $bubble = true)
    {
        $this->cockpit = $cockpit;

        parent::__construct($level, $bubble);
    }

    /**
     * @param array $record
     */
    protected function write(array $record): void
    {
        if (isset($record['context']['exception']) && $record['context']['exception'] instanceof Throwable) {
            // TODO: Implement cockpit exception handling
        }
    }
}
