<?php

namespace Cockpit;

use Cockpit\Models\Error;
use Throwable;

class Cockpit
{
    public static function handle(Throwable $throwable, $fileType = 'php', array $customData = [])
    {
        $error = Error::query()
            ->where('exception', '=', get_class($throwable))
            ->where('message', '=', $throwable->getMessage())
            ->where('type', '=', 'web')
            ->first();

        if ($error) {
            $error->occurrences++;
            $error->affected_users++;
            $error->last_occurrence_at = now();

            $error->save();
        } else {
            Error::query()->create([
                'type'               => 'web',
                'exception'          => get_class($throwable),
                'message'            => $throwable->getMessage(),
                'code'               => $throwable->getCode(),
                'url'                => app('request')->fullUrl(),
                'file'               => $throwable->getFile(),
                'trace'              => static::getTrace($throwable->getTrace()),
                'occurrences'        => 1,
                'affected_users'     => 1,
                'last_occurrence_at' => now(),
            ]);
        }
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
                'preview'  => FileContext::getContext($item['file'], $item['line']),
            ];
        }

        return $trace;
    }
}
