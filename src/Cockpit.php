<?php

namespace Cockpit;

use Cockpit\Context\AppContext;
use Cockpit\Context\CommandContext;
use Cockpit\Context\LivewireContext;
use Cockpit\Context\StackTraceContext;
use Cockpit\Context\UserContext;
use Cockpit\Models\Error;
use Illuminate\Foundation\Application;
use Throwable;

class Cockpit
{
    protected $app;

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

    public static function setUserHiddenFields(array $userHiddenFields): void
    {
        static::$userHiddenFields = $userHiddenFields;
    }

    protected function getExceptionType(): string
    {
        return $this->runningInCli() ? Error::TYPE_CLI : Error::TYPE_WEB;
    }
}
