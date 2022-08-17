<?php

namespace Cockpit;

use Closure;
use Illuminate\Http\Request;

class Cockpit
{
    public static ?Closure $authUsing = null;

    public static array $userHiddenFields = [];

    protected static array $hideFromRequest = ['password', 'password_confirmation'];

    protected static array $hideFromHeaders = ['authorization'];

    public static function setUserHiddenFields(array $userHiddenFields): void
    {
        static::$userHiddenFields = $userHiddenFields;
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

    public static function hideFromRequest(array $fields): void
    {
        self::$hideFromRequest = array_merge(['password', 'password_confirmation'], $fields);
    }

    public static function getHideFromRequest(): array
    {
        return self::$hideFromRequest;
    }

    public static function hideFromHeaders(array $headers): void
    {
        self::$hideFromHeaders = array_merge(['authorization'], $headers);
    }

    public static function getHideFromHeaders(): array
    {
        return self::$hideFromHeaders;
    }
}
