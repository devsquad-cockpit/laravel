<?php

namespace Cockpit;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Cockpit
{
    public static ?Closure $authUsing = null;

    public static array $userHiddenFields = [];

    protected static array $hideFromRequest = ['password', 'password_confirmation'];

    protected static array $hideFromHeaders = ['authorization'];

    protected static array $hideFromCookies = [];

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
        $headers = array_map(fn (string $value) => Str::lower($value), $headers);

        self::$hideFromHeaders = array_merge(['authorization'], $headers);
    }

    public static function getHideFromHeaders(): array
    {
        return self::$hideFromHeaders;
    }

    public static function hideFromCookies(array $cookies): void
    {
        $cookies = array_map(fn (string $value) => Str::lower($value), $cookies);

        self::$hideFromCookies = $cookies;
    }

    public static function getHideFromCookies(): array
    {
        return self::$hideFromCookies;
    }
}
