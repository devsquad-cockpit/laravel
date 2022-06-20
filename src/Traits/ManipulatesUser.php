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
    protected static $guardedUserFields = [];

    public static function setGuardedUserFields(array $guardedUserFields): void
    {
        static::$guardedUserFields = $guardedUserFields;
    }

    public function resolveUser(): ?array
    {
        if ($this->runningInCli() || !$user = $this->app->get('request')->user()) {
            return null;
        }

        if (empty(self::$guardedUserFields)) {
            return $user->toArray();
        }

        return Arr::except($user->toArray(), self::$guardedUserFields);
    }
}
