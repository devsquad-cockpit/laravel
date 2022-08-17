<?php

use Cockpit\Cockpit;
use Illuminate\Http\Request;

it('should return default value for authentication if authUsing is not be set', function () {
    expect(Cockpit::check(new Request()))
        ->toBe(app()->isLocal());
});

it('should be check auth with success', function ($value) {
    $cockpit = app(Cockpit::class);
    $cockpit->auth(fn () => $value);

    expect($cockpit->check(new Request))
        ->toBeBool()
        ->toBe($value);
})->with([
    true,
    false,
]);

it('should be set and get user hidden fields', function () {
    $cockpit = app(Cockpit::class);
    $cockpit->setUserHiddenFields(['password', 'email']);

    expect($cockpit::$userHiddenFields)
        ->toBeArray()
        ->toHaveCount(2)
        ->toMatchArray(['password', 'email']);
});

it('should set and get fields that should be hidden on request', function () {
    Cockpit::hideFromRequest(['email']);

    expect(Cockpit::$hideFromRequest)
        ->toBeArray()
        ->toHaveCount(3)
        ->toMatchArray(['password', 'password_confirmation', 'email']);
});
