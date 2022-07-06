<?php

namespace Cockpit;

use Closure;
use Cockpit\Context\AppContext;
use Cockpit\Context\CommandContext;
use Cockpit\Context\JobContext;
use Cockpit\Context\LivewireContext;
use Cockpit\Context\StackTraceContext;
use Cockpit\Context\UserContext;
use Cockpit\Models\Error;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Throwable;

class Cockpit
{
    protected $app;

    public static $authUsing;

    protected static $userHiddenFields = [];

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
        $traceContext    = app(StackTraceContext::class, ['throwable' => $throwable]);
        $userContext     = app(UserContext::class, ['hiddenFields' => self::$userHiddenFields]);
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

    protected function runningInCli(): bool
    {
        return $this->app->runningInConsole();
    }

    protected function calculateAffectedUsers(Error $error): int
    {
        return $this->runningInCli() ? $error->affected_users : $error->affected_users + 1;
    }

    protected function resolveUrl(): ?string
    {
        return !$this->runningInCli()
            ? $this->app->get('request')->fullUrl()
            : null;
    }

    protected function getExceptionType(): string
    {
        if (!$this->runningInCli()) {
            return Error::TYPE_WEB;
        }

        return $this->isExceptionFromJob() ? Error::TYPE_JOB : Error::TYPE_CLI;
    }

    protected function isExceptionFromJob(): bool
    {
        return is_array(app(JobContext::class)->getContext());
    }

    public static function setUserHiddenFields(array $userHiddenFields): void
    {
        static::$userHiddenFields = $userHiddenFields;
    }

    public static function check(Request $request)
    {
        return (static::$authUsing ?: function() {
            return app()->environment('local');
        })($request);
    }

    public static function auth(Closure $callback)
    {
        static::$authUsing = $callback;
    }
}
