<?php

// namespace Tests\Unit\Middleware;

use Cockpit\Cockpit;
use Cockpit\Http\Middleware\Authenticate;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

it('should be authenticate user with success', function () {
    $cockpit = app(Cockpit::class);
    $cockpit->auth(fn () => true);

    $middleware = app(Authenticate::class);

    $response = $middleware->handle(new Request, function () {
        return "Success";
    });

    expect($response)->toBe("Success");
});

it('should be not authenticate user throw unauthorized exception', function () {
    $cockpit = app(Cockpit::class);
    $cockpit->auth(fn () => false);

    $middleware = app(Authenticate::class);

    try {
        $response = $middleware->handle(new Request, function () {
            return "Success";
        });
        dd($response);
    } catch (HttpException $error) {
        expect($error)
            ->toBeInstanceOf(HttpException::class)
            ->and($error->getStatusCode())
            ->toBe(403);
    }
});
