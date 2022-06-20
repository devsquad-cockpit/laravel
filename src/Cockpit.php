<?php

namespace Cockpit;

use Cockpit\Models\Error;
use Cockpit\Traits\ManipulatesUser;
use Illuminate\Foundation\Application;
use Spatie\Backtrace\Backtrace;
use Spatie\Backtrace\CodeSnippet;
use Spatie\Backtrace\Frame;
use Throwable;

class Cockpit
{
    protected $app;

    use ManipulatesUser;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public static function handle(Throwable $throwable, $fileType = 'php', array $customData = [])
    {
        (new self(app()))
            ->execute($throwable, $fileType, $customData);
    }

    public function execute(Throwable $throwable, $fileType = 'php', array $customData = [])
    {
        /** @var Error $error */
        $error = Error::query()->firstOrNew([
            'exception'   => get_class($throwable),
            'message'     => $throwable->getMessage(),
            'resolved_at' => null,
        ]);

        $error->fill([
            'type'               => 'web',
            'url'                => $this->resolveUrl(),
            'code'               => $throwable->getCode(),
            'file'               => $throwable->getFile(),
            'trace'              => $this->getTrace($throwable),
            'user'               => $this->resolveUser(),
            'occurrences'        => $error->occurrences + 1,
            'affected_users'     => $this->calculateAffectedUsers($error),
            'last_occurrence_at' => now(),
        ])->save();
    }

    protected function getTrace(Throwable $throwable): array
    {
        $trace = [];

        $backTrace = Backtrace::createForThrowable($throwable)
                              ->applicationPath($this->app->basePath());

        foreach ($backTrace->frames() as $frame) {
            $trace[] = [
                'file'              => $frame->file,
                'line'              => $frame->lineNumber,
                'function'          => $frame->method,
                'class'             => $frame->class,
                'application_frame' => $frame->applicationFrame,
                'preview'           => $this->resolveFilePreview($frame),
            ];
        }

        return $trace;
    }

    protected function runningInCli(): bool
    {
        return $this->app->runningInConsole();
    }

    protected function calculateAffectedUsers(Error $error): int
    {
        return $this->runningInCli() ? $error->affected_users : $error->affected_users + 1;
    }

    protected function resolveFilePreview(Frame $frame): array
    {
        return (new CodeSnippet())
            ->surroundingLine($frame->lineNumber)
            ->snippetLineCount(20)
            ->get($frame->file);
    }

    protected function resolveUrl(): ?string
    {
        return !$this->runningInCli()
            ? $this->app->get('request')->fullUrl()
            : null;
    }
}
