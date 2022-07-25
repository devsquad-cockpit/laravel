<?php

namespace Cockpit\Tests\Unit\Context;

use Cockpit\Context\AppContext;
use Cockpit\Tests\Fixtures\Controllers\TestController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Mockery\MockInterface;
use Spatie\LaravelIgnition\Exceptions\ViewException;

function mockRouter(
    bool $named = false,
    bool $hasParameters = false,
    array $middleware = []
) {
    $routeName = $hasParameters ? '/test/{status}' : '/test';

    $router = app('router');
    $route  = $router->get($routeName, [TestController::class, 'index']);

    if ($named) {
        $route->name('cockpit.test');
    }

    if (!empty($middleware)) {
        $route->middleware($middleware);
    }

    $router->dispatchToRoute(
        Request::create($hasParameters ? '/test/active' : '/test')
    );

    return $router;
}

it('should return null if application is running in console', function () {
    $context = new AppContext($this->app, new InvalidArgumentException());

    expect($context->getContext())
        ->toBeNull();
});

it('should return a basic context data', function () {
    $throwable = new InvalidArgumentException();

    $router = mockRouter();

    $app = $this->partialMock(Application::class, function (MockInterface $mock) {
        $mock->shouldReceive('runningInConsole')->andReturn(false);
    });

    $app->singleton('router', fn () => $router);

    $context = new AppContext($app, $throwable);

    $this->assertEquals([
        'controller' => TestController::class . '@index',
        'route'      => [
            'name'       => 'generated::' . md5($router->current()->getActionName()),
            'parameters' => $router->current()->parameters(),
        ],
        'middlewares' => [],
        'view'        => [
            'name' => null,
            'data' => null,
        ],
    ], $context->getContext());
});

it('should retrieve route name when defined', function () {
    $throwable = new InvalidArgumentException();

    $router = mockRouter(true);

    $app = $this->partialMock(Application::class, function (MockInterface $mock) {
        $mock->shouldReceive('runningInConsole')->andReturn(false);
    });

    $app->singleton('router', fn () => $router);

    $context = new AppContext($app, $throwable);

    $this->assertEquals([
        'controller' => TestController::class . '@index',
        'route'      => [
            'name'       => 'cockpit.test',
            'parameters' => [],
        ],
        'middlewares' => [],
        'view'        => [
            'name' => null,
            'data' => null,
        ],
    ], $context->getContext());
});

it('should retrieve parameters when present on route', function () {
    $throwable = new InvalidArgumentException();

    $router = mockRouter(true, true);

    $app = $this->partialMock(Application::class, function (MockInterface $mock) {
        $mock->shouldReceive('runningInConsole')->andReturn(false);
    });

    $app->singleton('router', fn () => $router);

    $context = new AppContext($app, $throwable);

    $this->assertEquals([
        'controller' => TestController::class . '@index',
        'route'      => [
            'name'       => 'cockpit.test',
            'parameters' => [
                'status' => 'active',
            ],
        ],
        'middlewares' => [],
        'view'        => [
            'name' => null,
            'data' => null,
        ],
    ], $context->getContext());
});

it('should retrieve middlewares when present on route', function () {
    $throwable = new InvalidArgumentException();

    $router = mockRouter(true, false, ['guest']);

    $app = $this->partialMock(Application::class, function (MockInterface $mock) {
        $mock->shouldReceive('runningInConsole')->andReturn(false);
    });

    $app->singleton('router', fn () => $router);

    $context = new AppContext($app, $throwable);

    $this->assertEquals([
        'controller' => TestController::class . '@index',
        'route'      => [
            'name'       => 'cockpit.test',
            'parameters' => [],
        ],
        'middlewares' => ['guest'],
        'view'        => [
            'name' => null,
            'data' => null,
        ],
    ], $context->getContext());
});

it('should retrieve view information when throwable is an instance of ViewException', function () {
    $throwable = new ViewException();
    $throwable->setView('resources/app/my-view.blade.php');
    $throwable->setViewData(['myData' => Str::random()]);

    $router = mockRouter(true);

    $router->dispatchToRoute(Request::create('test'));

    $app = $this->partialMock(Application::class, function (MockInterface $mock) {
        $mock->shouldReceive('runningInConsole')->andReturn(false);
    });

    $app->singleton('router', fn () => $router);

    $context = new AppContext($app, $throwable);

    $this->assertEquals([
        'controller' => TestController::class . '@index',
        'route'      => [
            'name'       => 'cockpit.test',
            'parameters' => [],
        ],
        'middlewares' => [],
        'view'        => [
            'name' => $throwable->getFile(),
            'data' => $throwable->getViewData(),
        ],
    ], $context->getContext());
});
