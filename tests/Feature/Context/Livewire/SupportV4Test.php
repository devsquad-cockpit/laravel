<?php

namespace Cockpit\Tests\Feature\Context\Livewire;

use Cockpit\Context\Livewire\SupportV4;
use Cockpit\Tests\TestCase;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;

class SupportV4Test extends TestCase
{
    private function requestContent(): string
    {
        return file_get_contents(__DIR__ . '/../../../Fixtures/Livewire/v4request.json');
    }

    #[Test]
    public function it_can_resolve_data(): void
    {
        app()->bind(Request::class, fn() => Request::create(
            '/reset-password',
            'GET',
            server: ['HTTP_ACCEPT' => 'application/json'],
            content: $this->requestContent(),
        ));

        $fullPath = __DIR__ . '/../../../Fixtures/Livewire/ResetPassword.php';

        app()->bind('livewire.finder', fn() => new class($fullPath) {
            public function __construct(private $path) {}
            public function resolveClassComponentClassName(string $alias) { return $this->path; }
            public function resolveSingleFileComponentPath(string $alias) { return null; }
            public function resolveMultiFileComponentPath(string $alias) { return null; }
        });

        $expected = [
            "email" => "joe@devsquad.com",
            "password" => "adsf1234",
            "remember" => false
        ];

        $this->assertSame($expected, app(SupportV4::class)->information()['data']);
    }

    #[Test]
    public function it_can_resolve_updates(): void
    {
        app()->bind(Request::class, fn() => Request::create(
            '/',
            'GET',
            server: ['HTTP_ACCEPT' => 'application/json'],
            content: $this->requestContent(),
        ));

        $fullPath = __DIR__ . '/../../../Fixtures/Livewire/ResetPassword.php';

        app()->bind('livewire.finder', fn() => new class($fullPath) {
            public function __construct(private $path) {}
            public function resolveClassComponentClassName(string $alias) { return $this->path; }
            public function resolveSingleFileComponentPath(string $alias) { return null; }
            public function resolveMultiFileComponentPath(string $alias) { return null; }
        });

        $expected = [
            "email" => "dev@devsquad.com",
        ];

        $this->assertSame($expected, app(SupportV4::class)->information()['updates']);
    }
}
