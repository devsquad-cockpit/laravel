<?php

namespace Cockpit;

use Cockpit\Models\Error;
use Illuminate\Support\Facades\Route;
use Spatie\Backtrace\Backtrace;
use Spatie\Backtrace\CodeSnippet;
use Spatie\Backtrace\Frame;
use Spatie\LaravelIgnition\Exceptions\ViewException;
use Throwable;

class Cockpit
{
    public static function handle(Throwable $throwable, $fileType = 'php', array $customData = [])
    {
        $error = Error::query()->firstOrNew([
            'exception'   => get_class($throwable),
            'message'     => $throwable->getMessage(),
            'resolved_at' => null,
        ]);

        $error->fill([
            'type'               => 'web',
            'url'                => app('request')->fullUrl(),
            'code'               => $throwable->getCode(),
            'file'               => $throwable->getFile(),
            'trace'              => static::getTrace($throwable),
            'app'                => static::getApp($throwable),
            'occurrences'        => $error->occurrences + 1,
            'affected_users'     => self::calculateAffectedUsers($error),
            'last_occurrence_at' => now(),
        ])->save();
    }

    protected static function getApp(Throwable $throwable)
    {
        $route  = Route::current();
        $action = $route->getAction();

        $isViewException = $throwable instanceof ViewException;

        return [
            'controller' => $route->getActionName(),
            'route'      => [
                'name'       => $action['as'] ?? 'generated::' . md5($route->getActionName()),
                'parameters' => $route->parameters(),
            ],
            'middlewares' => $route->computedMiddleware,
            'view'        => [
                'name' => $isViewException ? $throwable->getFile() : null,
                'data' => $isViewException ? $throwable->getViewData() : null,
            ],
        ];
    }

    protected static function getTrace(Throwable $throwable): array
    {
        $trace = [];

        $backTrace = Backtrace::createForThrowable($throwable)
                              ->applicationPath(app()->basePath());
        ray($backTrace);

        foreach ($backTrace->frames() as $frame) {
            $trace[] = [
                'file'              => $frame->file,
                'line'              => $frame->lineNumber,
                'function'          => $frame->method,
                'class'             => $frame->class,
                'application_frame' => $frame->applicationFrame,
                'preview'           => self::resolveFilePreview($frame),
            ];
        }

        return $trace;
    }

    protected static function runningInCli(): bool
    {
        return app()->runningInConsole();
    }

    protected static function calculateAffectedUsers(Error $error): int
    {
        return self::runningInCli() ? $error->affected_users : $error->affected_users + 1;
    }

    protected static function resolveFilePreview(Frame $frame): array
    {
        return (new CodeSnippet())
            ->surroundingLine($frame->lineNumber)
            ->snippetLineCount(20)
            ->get($frame->file);
    }
}
