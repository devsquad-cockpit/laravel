<?php

namespace Cockpit\Context;

use Cockpit\Interfaces\ContextInterface;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UserContext implements ContextInterface
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getContext(): array
    {
        $request = $this->app->make(Request::class);

        if (($this->app->runningInConsole() && !app()->runningUnitTests()) || !$user = $request->user()) {
            return [];
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
