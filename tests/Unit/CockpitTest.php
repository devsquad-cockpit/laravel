<?php

use Cockpit\Cockpit;
use Illuminate\Http\Request;

it('should be check auth with success', function ($value) {
    $cockpit = app(Cockpit::class);
    $cockpit->auth(fn () => $value);

    expect($cockpit->check(new Request))
        ->toBeBool()
        ->toBe($value);
})->with([
    true,
    false
]);

it('should be set and get user hidden fields', function () {
    $cockpit = app(Cockpit::class);
    $cockpit->setUserHiddenFields(['password', 'email']);

    expect($cockpit->getUserHiddenFields())
        ->toBeArray()
        ->toHaveCount(2)
        ->toMatchArray(['password', 'email']);
});
