<?php

use Cockpit\Cockpit;
use Illuminate\Http\Request;

it('should return default value for authentication if authUsing is not be set', function () {
    expect(Cockpit::check(new Request()))
        ->toBe(app()->isLocal());
});

it('should be check auth with success', function ($value) {
    $cockpit = app(Cockpit::class);
    $cockpit->auth(function () use ($value) {
        return $value;
    });

    expect($cockpit->check(new Request))
        ->toBeBool()
        ->toBe($value);
})->with([
    true,
    false,
]);
