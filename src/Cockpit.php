<?php

namespace Cockpit;

use Closure;
use Illuminate\Http\Request;

class Cockpit
{
    public static ?Closure $authUsing = null;

    public static array $userHiddenFields = [];

    public static array $hideFromRequest = [];

    public static function setUserHiddenFields(array $userHiddenFields): void
    {
        static::$userHiddenFields = $userHiddenFields;
    }

    public static function hideFromRequest(array $fields): void
    {
        self::$hideFromRequest = array_merge(['password', 'password_confirmation'], $fields);
    }

    public static function check(Request $request)
    {
        return (static::$authUsing ?: function () {
            return app()->isLocal();
        })($request);
    }

    public static function auth(Closure $callback): void
    {
        static::$authUsing = $callback;
    }
}
