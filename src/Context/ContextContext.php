<?php

namespace Cockpit\Context;

use Cockpit\Interfaces\ContextInterface;
use Illuminate\Support\Arr;
use Monolog\LogRecord;

class ContextContext implements ContextInterface
{
    public function __construct(protected LogRecord $record)
    {}

    public function getContext(): ?array
    {
        return collect(Arr::except($this->record->context, ['exception']))
            ->merge(Arr::except($this->record->extra, ['cockpit_context']))
            ->merge(Arr::get($this->record->extra, 'cockpit_context', []))
            ->toArray();
    }
}
