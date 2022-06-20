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

    public function resolveUser(): ?array
    {
        if ($this->runningInCli() || !$user = $this->app->get('request')->user()) {
            return null;
        }

        if (empty(self::$userHiddenFields)) {
            return $user->toArray();
        }

        return Arr::except($user->toArray(), self::$userHiddenFields);
    }
}
