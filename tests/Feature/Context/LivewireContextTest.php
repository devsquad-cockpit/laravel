<?php

namespace Cockpit\Tests\Feature\Context;

use Illuminate\Http\Request;
use Cockpit\Context\RequestContext;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Cockpit\Context\LivewireContext;

const APP_SESSION = 'eyJpdiI6IkRIQU1CUHhLS3loNlU5VzNsUHZRcnc9PSIsInZhbHVlIjoiRW5zbnI5N0F0eGQ1dGxmV2h6OU9Ddz09IiwibWFjIjoiZWFmMGZiODUwMWQxY2IzNjI5OGUyYTU1NjUwNDUyZDNiZDk4NjY5YTk5OTk5MTUyZjNmNzI3NmE3NWRhNjcxNCIsInRhZyI6IiJ9';

it('should not return a livewire response', function () {

    $request = Request::create(
        '/update/',
        'PUT',
        [],
        ['app_session' => APP_SESSION],
        [],
        ['HTTP_ACCEPT' => 'application/json']
    );

    app()->bind(Request::class, fn () => $request);

    $context = app(RequestContext::class)->getContext();

    expect($context)
        ->toBeArray()
        ->not
        ->toBeEmpty()
        ->and($context['request']['method'])
        ->toBe('PUT')
        ->and($context['headers'])
        ->not
        ->toContain('x-livewire');
});

it('should return a livewire response', function () {

    $id   = uniqid();
    $user = new User();

    $user->forceFill([
        'id' => rand(1, 100),
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('password'),
    ]);

    $request = Request::create(
        '/update/',
        'PUT',
        [],
        ['app_session' => \Cockpit\Tests\Unit\Context\APP_SESSION],
        [],
        ['HTTP_ACCEPT' => 'application/json']
    );

    app()->bind(Request::class, fn () => $request);

    $request->setUserResolver(fn () => $user);

    $request = Request::create(
        '/update/',
        'GET',
        [
            'fingerprint' => [
                'id'     => $id,
                'method' => 'PUT',
                'name'   => 'update-component',
                'path'   => 'update',
            ],
            'serverMemo' => [
                'data' => [
                    'user' => []
                ],
                'dataMeta' => [
                    'models' => [
                        'user' => [
                            'class'      => 'App\Models\User',
                            'connection' => 'mysql',
                            'id'         => $user->id,
                            'relations'  => []
                        ],
                    ]
                ],
            ],
            'updates' => [],
        ],
        ['app_session' => APP_SESSION],
        [],
        ['HTTP_ACCEPT' => 'application/json']
    );

    $request->headers->set('x-livewire', 'true');
    $request->headers->set('referer', 'http://localhost/update/');
    $request->headers->set('content-type', 'application/json');

    app()->bind(Request::class, fn () => $request);

    $livewire = app(LivewireContext::class)->getContext();

    expect($livewire)
        ->toBeArray()
        ->not
        ->toBeEmpty()
        ->and($livewire['method'])
        ->toBe('GET')
        ->and($livewire['component_class'])
        ->toBeNull()
        ->and($livewire['component_id'])
        ->toBe($id)
        ->and($livewire['component_alias'])
        ->toBe('update-component')
        ->and($livewire['data']['user']['id'])
        ->toBe($user->id);
});
