<?php

namespace Cockpit;

use Throwable;

class Cockpit
{
    public static function handle(Throwable $throwable, $fileType = 'php', array $customData = [])
    {
        $error = \Cockpit\Models\Error::create([
            'exception'      => get_class($throwable),
            'occurrences'    => 1,
            'affected_users' => 1,
        ]);

        $error->occurrences()->create([
            'url'     => app('request')->fullUrl(),
            'type'    => 'web',
            'message' => $throwable->getMessage(),
            'code'    => $throwable->getCode(),
            'file'    => $throwable->getFile(),
            'trace'   => static::getTrace($throwable->getTrace()),
        ]);
    }

    protected static function getTrace(array $stackTrace): array
    {
        $trace = [];

        foreach ($stackTrace as $item) {
            $trace[] = [
                'file'     => $item['file'],
                'line'     => $item['line'],
                'function' => $item['function'],
                'class'    => $item['class'] ?? null,
                'preview'  => FileContext::getContext($item['file'], $item['line'])
            ];
        }

        return $trace;
    }
}
