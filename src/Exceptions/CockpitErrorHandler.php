<?php

namespace Cockpit\Exceptions;

use Cockpit\Cockpit;
use Cockpit\Context\{AppContext, CommandContext, JobContext, LivewireContext, StackTraceContext, UserContext};
use Cockpit\Models\Error;
use Monolog\Handler\AbstractProcessingHandler;
use Throwable;

class CockpitErrorHandler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        if (!$this->shouldReport($record)) {
            return;
        }

        Cockpit::handle($record['context']['exception']);
    }

    protected function shouldReport(array $report): bool
    {
        return $this->hasException($report);
    }

    protected function hasException(array $report): bool
    {
        return isset($report['context']['exception']) && $report['context']['exception'] instanceof Throwable;
    }

    protected function log(Throwable $throwable)
    {
        $traceContext    = app(StackTraceContext::class, ['throwable' => $throwable]);
        $userContext     = app(UserContext::class, ['hiddenFields' => Cockpit::$userHiddenFields]);
        $appContext      = app(AppContext::class, ['throwable' => $throwable]);
        $commandContext  = app(CommandContext::class);
        $livewireContext = app(LivewireContext::class);
        $jobContext      = app(JobContext::class);

        /** @var Error $error */
        $error = Error::query()->firstOrNew([
            'exception'   => get_class($throwable),
            'message'     => $throwable->getMessage(),
            'resolved_at' => null,
        ]);

        $error->fill([
            'type'               => $this->getExceptionType(),
            'url'                => $this->resolveUrl(),
            'code'               => $throwable->getCode(),
            'file'               => $throwable->getFile(),
            'trace'              => $traceContext->getContext(),
            'user'               => $userContext->getContext(),
            'app'                => $appContext->getContext(),
            'command'            => $commandContext->getContext(),
            'livewire'           => $livewireContext->getContext(),
            'job'                => $jobContext->getContext(),
            'occurrences'        => $error->occurrences + 1,
            'affected_users'     => $this->calculateAffectedUsers($error),
            'last_occurrence_at' => now(),
        ])->save();
    }

    protected function calculateAffectedUsers(Error $error): int
    {
        return app()->runningInConsole() ? $error->affected_users : $error->affected_users + 1;
    }

    protected function resolveUrl(): ?string
    {
        return !app()->runningInConsole()
            ? app('request')->fullUrl()
            : null;
    }

    protected function getExceptionType(): string
    {
        if (!app()->runningInConsole()) {
            return Error::TYPE_WEB;
        }

        return $this->isExceptionFromJob() ? Error::TYPE_JOB : Error::TYPE_CLI;
    }

    protected function isExceptionFromJob(): bool
    {
        return is_array(app(JobContext::class)->getContext());
    }
}
