<?php

namespace Cockpit;

use Illuminate\Support\Facades\Context;
use Throwable;

function report(string|Throwable $exception, array $context = []): void
{
    if (filled($context)) {
        Context::add('cockpit_context', $context);
    }

    \report($exception);
}
