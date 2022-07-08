<?php

namespace Cockpit\Context;

use Cockpit\Interfaces\ContextInterface;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UserContext implements ContextInterface
{
    protected Application $app;

    protected array $hiddenFields = [];

    public function __construct(Application $app, array $hiddenFields)
    {
        $this->app          = $app;
        $this->hiddenFields = $hiddenFields;
    }

    public function getContext(): ?array
    {
        $request = $this->app->make(Request::class);

        if ($this->app->runningInConsole() || !$user = $request->user()) {
            return null;
        }

        $userData = empty($this->hiddenFields)
            ? $user->toArray()
            : Arr::except($user->toArray(), $this->hiddenFields);

        return [
            'guard' => $this->getAuthGuard(),
        ] + $userData;
    }

    protected function getAuthGuard(): ?string
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (auth($guard)->check()) {
                return $guard;
            }
        }

        return null;
    }
}
