<?php

namespace Cockpit\Traits;

use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;

/**
 * @property-read Application $app
 * @method bool runningInCli()
 */
trait ManipulatesUser
{
    protected static $userHiddenFields = [];

    public static function setUserHiddenFields(array $userHiddenFields): void
    {
        static::$userHiddenFields = $userHiddenFields;
    }

    protected function resolveUser(): ?array
    {
        if ($this->runningInCli() || !$user = $this->app->get('request')->user()) {
            return null;
        }

        $userData = empty(self::$userHiddenFields)
            ? $user->toArray()
            : Arr::except($user->toArray(), self::$userHiddenFields);

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

        return  null;
    }
}
