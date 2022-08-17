<?php

namespace Cockpit;

use Closure;
use Illuminate\Http\Request;

class Cockpit
{
    public static Closure $authUsing;

    public static array $userHiddenFields = [];

    protected static array $hideFromRequest = [
        'password',
        'password_confirmation'
    ];

    public static function setUserHiddenFields(array $userHiddenFields): void
    {
        static::$userHiddenFields = $userHiddenFields;
    }

    public static function hideFromRequest(array $fields): void
    {
        self::$hideFromRequest = array_merge(self::$hideFromRequest, $fields);
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
