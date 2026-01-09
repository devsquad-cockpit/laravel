<?php

namespace Cockpit\Tests\Feature\Context;

use Cockpit\Context\LivewireContext;
use Cockpit\Tests\TestCase;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

class LivewireContextTest extends TestCase
{
    #[Test]
    public function it_is_running_livewire(): void
    {
        $request = Request::create('/update/');
        $request->headers->set('x-livewire', 'true');
        $request->headers->set('referer', 'http://localhost/update/');
        $request->headers->set('content-type', 'application/json');

        app()->bind(Request::class, fn() => $request);

        $this->assertTrue(app(LivewireContext::class)->isRunningLivewire());
    }

    #[Test]
    public function it_should_return_a_livewire_response_v3(): void
    {
        $ref = new \ReflectionProperty(LivewireContext::class, 'version');
        $ref->setValue(null, 'v3.0.0');

        $this->mock(\Livewire\Mechanisms\ComponentRegistry::class, function (MockInterface $mock) {
            $mock->shouldReceive('getClass')->andReturn('Login');
        });

        $this->mock("\Livewire\LivewireManager", function (MockInterface $mock) {
            $mock->shouldReceive('originalUrl')->once()->andReturn('http://localhost');
            $mock->shouldReceive('originalMethod')->once()->andReturn('GET');
        });

        app()->bind(Request::class, function () {
            $request = Request::create(
                '/update/',
                server: ['HTTP_ACCEPT' => 'application/json'],
                content: file_get_contents(__DIR__ . '/../../Fixtures/Livewire/v3request.json')
            );

            $request->setUserResolver(fn() => tap(new User())->forceFill([
                'id' => 100,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
            ]));

            $request->headers->set('x-livewire', 'true');
            $request->headers->set('referer', 'http://localhost/update/');
            $request->headers->set('content-type', 'application/json');

            return $request;
        });

        $context = app(LivewireContext::class)->getContext();

        $this->assertSame('GET', $context['method']);
        $this->assertSame('Login', $context['component_class']);
        $this->assertSame('5BaTZJr1CCCgB5M41wxT', $context['component_id']);
        $this->assertSame('auth.login', $context['component_alias']);
    }

    #[Test]
    public function it_should_return_a_livewire_response_v4(): void
    {
        $ref = new \ReflectionProperty(LivewireContext::class, 'version');
        $ref->setValue(null, 'v4.0.0');

        $this->mock(\Livewire\Finder\Finder::class, function (MockInterface $mock) {
            $mock->shouldReceive('resolveComponentClass')->andReturn('Login');
        });

        $fullPath = base_path() . DIRECTORY_SEPARATOR . 'app/Http/Livewire/Auth/Login.php';


        app()->bind('livewire.finder', fn() => new class($fullPath) {
            public function __construct(private $path) {}
            public function resolveClassComponentClassName(string $alias) { return $this->path; }
            public function resolveSingleFileComponentPath(string $alias) { return null; }
            public function resolveMultiFileComponentPath(string $alias) { return null; }
        });

        $this->mock("\Livewire\LivewireManager", function (MockInterface $mock) {
            $mock->shouldReceive('originalUrl')->once()->andReturn('http://localhost');
            $mock->shouldReceive('originalMethod')->once()->andReturn('GET');
        });

        app()->bind(Request::class, function () {
            $request = Request::create(
                '/update/',
                server: ['HTTP_ACCEPT' => 'application/json'],
                content: file_get_contents(__DIR__ . '/../../Fixtures/Livewire/v3request.json')
            );

            $request->setUserResolver(fn() => tap(new User())->forceFill([
                'id' => 100,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
            ]));

            $request->headers->set('x-livewire', 'true');
            $request->headers->set('referer', 'http://localhost/update/');
            $request->headers->set('content-type', 'application/json');

            return $request;
        });

        $context = app(LivewireContext::class)->getContext();

        $this->assertSame('GET', $context['method']);
        $this->assertSame('5BaTZJr1CCCgB5M41wxT', $context['component_id']);
        $this->assertSame('auth.login', $context['component_alias']);
    }
}
