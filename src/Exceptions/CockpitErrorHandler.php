<?php

namespace Cockpit\Exceptions;

use Cockpit\Cockpit;
use Cockpit\Context\AppContext;
use Cockpit\Context\CommandContext;
use Cockpit\Context\DumpContext;
use Cockpit\Context\JobContext;
use Cockpit\Context\LivewireContext;
use Cockpit\Context\RequestContext;
use Cockpit\Context\StackTraceContext;
use Cockpit\Context\UserContext;
use Cockpit\Models\Error;
use Cockpit\Models\Occurrence;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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
        $dumpContext     = app(DumpContext::class);
        $requestContext  = app(RequestContext::class);

        /** @var Error $error */
        $error = Error::query()->firstOrNew([
            'exception'   => get_class($throwable),
            'message'     => $throwable->getMessage(),
            'file'        => $throwable->getFile(),
            'code'        => $throwable->getCode(),
            'resolved_at' => null,
        ]);

        $this->createEntry($error, [
            'type'     => $this->getExceptionType(),
            'url'      => $this->resolveUrl(),
            'trace'    => $traceContext->getContext(),
            'debug'    => $dumpContext->getContext(),
            'app'      => $appContext->getContext(),
            'user'     => $userContext->getContext(),
            'context'  => $context,
            'request'  => $requestContext->getContext(),
            'command'  => $commandContext->getContext(),
            'job'      => $jobContext->getContext(),
            'livewire' => $livewireContext->getContext(),
        ]);
    }

    protected function createEntry(Error $error, array $occurrence)
    {
        DB::connection('cockpit')->transaction(function () use ($error, $occurrence) {
            $error->fill(['last_occurrence_at' => now()])->save();
            $error->occurrences()->create($occurrence);
        });
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
            return Occurrence::TYPE_WEB;
        }

        return $this->isExceptionFromJob() ? Occurrence::TYPE_JOB : Occurrence::TYPE_CLI;
    }

    protected function isExceptionFromJob(): bool
    {
        return is_array(app(JobContext::class)->getContext());
    }
}