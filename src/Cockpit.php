<?php

namespace Cockpit;

use Cockpit\Context\StackTraceContext;
use Cockpit\Models\Error;
use Cockpit\Traits\ManipulatesUser;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Spatie\Backtrace\Backtrace;
use Spatie\Backtrace\CodeSnippet;
use Spatie\Backtrace\Frame;
use Spatie\LaravelIgnition\Exceptions\ViewException;
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
            'trace'              => app(StackTraceContext::class)->createContextFromException($throwable),
            'user'               => $this->resolveUser(),
            'app'                => $this->getApp($throwable),
            'occurrences'        => $error->occurrences + 1,
            'affected_users'     => $this->calculateAffectedUsers($error),
            'last_occurrence_at' => now(),
        ])->save();
    }

    protected function getApp(Throwable $throwable)
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
}
