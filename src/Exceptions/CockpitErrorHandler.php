<?php

namespace Cockpit\Exceptions;

use Cockpit\Cockpit;
use Cockpit\Context\AppContext;
use Cockpit\Context\CommandContext;
use Cockpit\Context\DumpContext;
use Cockpit\Context\EnvironmentContext;
use Cockpit\Context\JobContext;
use Cockpit\Context\LivewireContext;
use Cockpit\Context\RequestContext;
use Cockpit\Context\StackTraceContext;
use Cockpit\Context\UserContext;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Throwable;

class CockpitErrorHandler extends AbstractProcessingHandler
{
    protected int $minimumLogLevel = Logger::ERROR;

    private ?Response $response = null;

    public function setMinimumLogLevel(int $level)
    {
        if (!in_array($level, Logger::getLevels())) {
            throw new InvalidArgumentException('The given log level is not supported');
        }

        $this->minimumLogLevel = $level;
    }

    public function write(array $record): void
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
        $traceContext       = app(StackTraceContext::class, ['throwable' => $throwable]);
        $userContext        = app(UserContext::class);
        $appContext         = app(AppContext::class, ['throwable' => $throwable]);
        $commandContext     = app(CommandContext::class);
        $livewireContext    = app(LivewireContext::class);
        $jobContext         = app(JobContext::class);
        $dumpContext        = app(DumpContext::class);
        $requestContext     = app(RequestContext::class);
        $environmentContext = app(EnvironmentContext::class);

        if (config('cockpit.enabled')) {
            if (!config('cockpit.route')) {
                throw new Exception('You need to fill COCKPIT_ROUTE env with a valid cockpit endpoint');
            }

            $this->response = Http::post(config('cockpit.route'), [
                'exception'   => get_class($throwable),
                'message'     => $throwable->getMessage(),
                'file'        => $throwable->getFile(),
                'code'        => $throwable->getCode(),
                'resolved_at' => null,
                'type'        => $this->getExceptionType(),
                'url'         => $this->resolveUrl(),
                'trace'       => $traceContext->getContext(),
                'debug'       => $dumpContext->getContext(),
                'app'         => $appContext->getContext(),
                'user'        => $userContext->getContext(),
                'context'     => $context,
                'request'     => $requestContext->getContext(),
                'command'     => $commandContext->getContext(),
                'job'         => $jobContext->getContext(),
                'livewire'    => $livewireContext->getContext(),
                'environment' => $environmentContext->getContext(),
            ]);
        }
    }

    public function failed(): ?bool
    {
        return $this->response
            ? $this->response->failed()
            : null;
    }

    public function reason(): ?string
    {
        return $this->response
            ? "Reason: {$this->response->status()} {$this->response->reason()}"
            : null;
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
            return Cockpit::TYPE_WEB;
        }

        return $this->isExceptionFromJob() ? Cockpit::TYPE_JOB : Cockpit::TYPE_CLI;
    }

    protected function isExceptionFromJob(): bool
    {
        return is_array(app(JobContext::class)->getContext());
    }
}
