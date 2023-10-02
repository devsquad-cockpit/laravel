<?php

namespace Cockpit\Tests\Feature\Context\Livewire;

use Cockpit\Context\Livewire\LivewireInformationV3;
use Cockpit\Tests\TestCase;
use Illuminate\Http\Request;

class LivewireInformationV3Test extends TestCase
{
    private function requestContent(): string
    {
        return file_get_contents(__DIR__ . '/../../../Fixtures/Livewire/v3request.json');
    }

    /** @test */
    public function it_can_resolve_data(): void
    {
        app()->bind(Request::class, fn() => Request::create(
            '/reset-password',
            'GET',
            server: ['HTTP_ACCEPT' => 'application/json'],
            content: $this->requestContent(),
        ));

        $expected = [
            "email" => "joe@devsquad.com",
            "password" => "asdf",
            "remember" => false
        ];

        $this->assertSame($expected, app(LivewireInformationV3::class)->information()['data']);
    }

    /** @test */
    public function it_can_resolve_updates(): void
    {
        app()->bind(Request::class, fn() => Request::create(
            '/',
            'GET',
            server: ['HTTP_ACCEPT' => 'application/json'],
            content: $this->requestContent(),
        ));

        $expected = [
            "email" => "asdf",
            "password" => "fda"
        ];

        $this->assertSame($expected, app(LivewireInformationV3::class)->information()['updates']);
    }
}