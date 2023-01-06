<?php

namespace Cockpit\Tests\Feature\Context;

use Cockpit\Context\LivewireContext;
use Cockpit\Context\RequestContext;
use Cockpit\Tests\TestCase;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LivewireContextTest extends TestCase
{
    private const APP_SESSION = 'eyJpdiI6IkRIQU1CUHhLS3loNlU5VzNsUHZRcnc9PSIsInZhbHVlIjoiRW5zbnI5N0F0eGQ1dGxmV2h6OU9Ddz09IiwibWFjIjoiZWFmMGZiODUwMWQxY2IzNjI5OGUyYTU1NjUwNDUyZDNiZDk4NjY5YTk5OTk5MTUyZjNmNzI3NmE3NWRhNjcxNCIsInRhZyI6IiJ9';

    /** @test */
    public function it_should_not_return_a_livewire_response(): void
    {
        $request = Request::create(
            '/update/',
            'PUT',
            [],
            ['app_session' => self::APP_SESSION],
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );

        app()->bind(Request::class, function () use ($request) {
            return $request;
        });

        $context = app(RequestContext::class)->getContext();

        $this->assertSame('PUT', $context['request']['method']);
        $this->assertArrayNotHasKey('x-livewire', $context['headers']);
    }

    /** @test */
    public function it_should_return_a_livewire_response(): void
    {
        $id   = uniqid();
        $user = new User();

        $user->forceFill([
            'id'       => rand(1, 100),
            'name'     => 'John Doe',
            'email'    => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        $request = Request::create(
            '/update/',
            'PUT',
            [],
            ['app_session' => self::APP_SESSION],
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );

        app()->bind(Request::class, function () use ($request) {
            return $request;
        });

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

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
            ['app_session' => self::APP_SESSION],
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );

        $request->headers->set('x-livewire', 'true');
        $request->headers->set('referer', 'http://localhost/update/');
        $request->headers->set('content-type', 'application/json');

        app()->bind(Request::class, function () use ($request) {
            return $request;
        });

        $livewire = app(LivewireContext::class)->getContext();

        $this->assertSame('GET', $livewire['method']);
        $this->assertSame(null, $livewire['component_class']);
        $this->assertSame($id, $livewire['component_id']);
        $this->assertSame('update-component', $livewire['component_alias']);
        $this->assertSame($user->id, $livewire['data']['user']['id']);
    }
}
