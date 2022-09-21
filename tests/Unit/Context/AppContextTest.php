<?php

namespace Cockpit\Tests\Unit\Context;

use Cockpit\Context\AppContext;
use Cockpit\Exceptions\ViewException;
use Cockpit\Tests\Fixtures\Controllers\TestController;
use Cockpit\Tests\TestCase;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Mockery\MockInterface;

class AppContextTest extends TestCase
{
    private function mockRouter(
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

    /** @test */
    public function it_should_return_an_empty_array_if_application_is_running_in_console(): void
    {
        $context = new AppContext($this->app, new InvalidArgumentException());

        $this->assertSame([], $context->getContext());
    }

    /** @test */
    public function it_should_return_a_basic_context_data(): void
    {
        $throwable = new InvalidArgumentException();

        $router = $this->mockRouter();

        $app = $this->partialMock(Application::class, function (MockInterface $mock) {
            $mock->shouldReceive('runningInConsole')->andReturn(false);
        });

        $app->singleton('router', function () use ($router) {
            return $router;
        });

        $context = new AppContext($app, $throwable);

        $this->assertSame($context->getContext(), [
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
        ]);
    }

    /** @test */
    public function it_should_retrieve_route_name_when_defined(): void
    {
        $throwable = new InvalidArgumentException();

        $router = $this->mockRouter(true);

        $app = $this->partialMock(Application::class, function (MockInterface $mock) {
            $mock->shouldReceive('runningInConsole')->andReturn(false);
        });

        $app->singleton('router', function () use ($router) {
            return $router;
        });

        $context = new AppContext($app, $throwable);

        $this->assertSame([
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
    }

    /** @test */
    public function it_should_retrieve_parameters_when_present_on_route(): void
    {
        $throwable = new InvalidArgumentException();

        $router = $this->mockRouter(true, true);

        $app = $this->partialMock(Application::class, function (MockInterface $mock) {
            $mock->shouldReceive('runningInConsole')->andReturn(false);
        });

        $app->singleton('router', function () use ($router) {
            return $router;
        });

        $context = new AppContext($app, $throwable);

        $this->assertSame([
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
    }

    /** @test */
    public function it_should_retrieve_middlewares_when_present_on_route(): void
    {
        $throwable = new InvalidArgumentException();

        $router = $this->mockRouter(true, false, ['guest']);

        $app = $this->partialMock(Application::class, function (MockInterface $mock) {
            $mock->shouldReceive('runningInConsole')->andReturn(false);
        });

        $app->singleton('router', function () use ($router) {
            return $router;
        });

        $context = new AppContext($app, $throwable);

        $this->assertSame([
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
    }

    /** @test */
    public function it_should_retrieve_view_information_when_throwable_is_an_instance_of_ViewException(): void
    {
        $throwable = new ViewException();
        $throwable->setView('resources/app/my-view.blade.php');
        $throwable->setViewData(['myData' => Str::random()]);

        $router = $this->mockRouter(true);

        $router->dispatchToRoute(Request::create('test'));

        $app = $this->partialMock(Application::class, function (MockInterface $mock) {
            $mock->shouldReceive('runningInConsole')->andReturn(false);
        });

        $app->singleton('router', function () use ($router) {
            return $router;
        });

        $context = new AppContext($app, $throwable);


        $this->assertSame([
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
    }
}
