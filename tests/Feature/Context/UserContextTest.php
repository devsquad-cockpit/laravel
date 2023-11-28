<?php

namespace Cockpit\Tests\Feature\Context;

use Cockpit\Context\UserContext;
use Cockpit\Tests\TestCase;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function app;

class UserContextTest extends TestCase
{
    /** @test */
    public function it_should_return_empty_array_when_there_isnt_database_connection(): void
    {
        app()->bind(Request::class, function () {
            throw new \PDOException;
        });

        $context = app(UserContext::class)->getContext();

        $this->assertSame([], $context);
    }

    /** @test */
    public function it_should_retrieve_an_empty_array_if_user_is_unauthenticated(): void
    {
        $request = Request::create(
            '/update/',
            'PUT',
            [],
            [],
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );

        app()->bind(Request::class, function () use ($request) {
            return $request;
        });

        $context = app(UserContext::class)->getContext();

        $this->assertSame([], $context);
    }

    /** @test */
    public function it_should_retrieve_the_authenticated_user(): void
    {
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
            [],
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );

        app()->bind(Request::class, function () use ($request) {
            return $request;
        });

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $context = app(UserContext::class)->getContext();

        $this->assertEquals(array_merge($user->toArray(), ['guard' => null]), $context);
    }

    /** @test */
    public function it_should_confirm_the_authenticated_user(): void
    {
        $john = new User();
        $john->forceFill([
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
            [],
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );

        app()->bind(Request::class, function () use ($request) {
            return $request;
        });

        $request->setUserResolver(function () use ($john) {
            return $john;
        });

        $context = app(UserContext::class)->getContext();

        $this->assertNotEquals(array_merge($will->toArray(), ['guard' => null]), $context);
    }
}
