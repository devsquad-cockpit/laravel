<?php

namespace Cockpit;

use Closure;
use Illuminate\Http\Request;

class Cockpit
{
    public static Closure $authUsing;

    public static array $userHiddenFields = [];

    public static function setUserHiddenFields(array $userHiddenFields): void
    {
        static::$userHiddenFields = $userHiddenFields;
    }

    public static function getUserHiddenFields(): array
    {
        return static::$userHiddenFields;
    }

    public static function check(Request $request)
    {
        return (static::$authUsing ?: function () {
            return app()->isLocal();
        })($request);
    }

    public static function auth(Closure $callback)
    {
        static::$authUsing = $callback;
    }
}
