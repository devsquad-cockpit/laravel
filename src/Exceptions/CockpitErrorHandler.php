<?php

namespace Cockpit\Exceptions;

use Cockpit\Cockpit;
use Cockpit\Context\AppContext;
use Cockpit\Context\CommandContext;
use Cockpit\Context\JobContext;
use Cockpit\Context\LivewireContext;
use Cockpit\Context\StackTraceContext;
use Cockpit\Context\UserContext;
use Cockpit\Models\Error;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Throwable;

class CockpitErrorHandler extends AbstractProcessingHandler
{
    protected int $minimumLogLevel = Logger::ERROR;

    public function setMinimumLogLevel(int $level)
    {
        if (!in_array($level, Logger::getLevels())) {
            throw new InvalidArgumentException('The given log level is not supported');
        }

        $this->minimumLogLevel = $level;
    }

    protected function write(array $record): void
    {
        if (!$this->shouldReport($record)) {
            return;
        }

        $this->log(
            $record['context']['exception'],
            Arr::except($record['context'], 'exception')
        );
    }

    protected function shouldReport(array $report): bool
    {
        return $this->hasException($report) && $this->hasValidLogLevel($report);
    }

    protected function hasException(array $report): bool
    {
        return isset($report['context']['exception'])
            && $report['context']['exception'] instanceof Throwable;
    }

    protected function hasValidLogLevel(array $report): bool
    {
        return $report['level'] >= $this->minimumLogLevel;
    }

    protected function log(Throwable $throwable, array $context = []): void
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
            'file'        => $throwable->getFile(),
            'code'        => $throwable->getCode(),
            'url'         => $this->resolveUrl(),
            'resolved_at' => null,
        ]);

        $error->fill(['last_occurrence_at' => now()])->save();

        $error->occurrences()->create([
            'type'     => $this->getExceptionType(),
            'trace'    => $traceContext->getContext(),
            'user'     => $userContext->getContext(),
            'app'      => $appContext->getContext(),
            'context'  => $context,
            'command'  => $commandContext->getContext(),
            'livewire' => $livewireContext->getContext(),
            'job'      => $jobContext->getContext(),
        ]);
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
