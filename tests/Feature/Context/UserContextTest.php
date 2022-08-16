<?php

namespace Cockpit\Tests\Unit\Context;

use Cockpit\Cockpit;
use Cockpit\Context\UserContext;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

beforeAll(fn () => Cockpit::setUserHiddenFields([]));

const APP_SESSION = 'eyJpdiI6IkRIQU1CUHhLS3loNlU5VzNsUHZRcnc9PSIsInZhbHVlIjoiRW5zbnI5N0F0eGQ1dGxmV2h6OU9Ddz09IiwibWFjIjoiZWFmMGZiODUwMWQxY2IzNjI5OGUyYTU1NjUwNDUyZDNiZDk4NjY5YTk5OTk5MTUyZjNmNzI3NmE3NWRhNjcxNCIsInRhZyI6IiJ9';

it('should retrieve an empty array if user is unauthenticated', function () {
    $request = Request::create(
        '/update/',
        'PUT',
        [],
        ['app_session' => APP_SESSION],
        [],
        ['HTTP_ACCEPT' => 'application/json']
    );

    app()->bind(Request::class, fn () => $request);

    $context = app(UserContext::class, ['hiddenFields' => Cockpit::$userHiddenFields])->getContext();

    expect($context)
        ->toBeArray()
        ->toBeEmpty();
});

it('should retrieve the authenticated user', function () {
    $user = new User();
    $user->forceFill([
        'id'       => 1,
        'name'     => 'John Doe',
        'email'    => 'john@example.com',
        'password' => Hash::make('password'),
    ]);

    $request = Request::create(
        '/update/',
        'PUT',
        [],
        ['app_session' => APP_SESSION],
        [],
        ['HTTP_ACCEPT' => 'application/json']
    );

    app()->bind(Request::class, fn () => $request);

    $request->setUserResolver(fn () => $user);

    $context = app(UserContext::class, ['hiddenFields' => Cockpit::$userHiddenFields])->getContext();

    $this->assertEquals(array_merge($user->toArray(), ['guard' => null]), $context);
});

it('should confirm the authenticated user', function () {
    $jhon = new User();
    $jhon->forceFill([
        'id'       => 1,
        'name'     => 'John Doe',
        'email'    => 'john@example.com',
        'password' => Hash::make('password'),
    ]);

    $will = new User();
    $will->forceFill([
        'id'       => 1,
        'name'     => 'Will N. Mag',
        'email'    => 'will@example.com',
        'password' => Hash::make('password'),
    ]);

    $request = Request::create(
        '/update/',
        'PUT',
        [],
        ['app_session' => APP_SESSION],
        [],
        ['HTTP_ACCEPT' => 'application/json']
    );

    app()->bind(Request::class, fn () => $request);

    $request->setUserResolver(fn () => $jhon);

    $context = app(UserContext::class, ['hiddenFields' => Cockpit::$userHiddenFields])->getContext();

    $this->assertNotEquals(array_merge($will->toArray(), ['guard' => null]), $context);
});
