<?php

namespace Cockpit\Tests\Feature\Context;

use Cockpit\Context\RequestContext;
use Cockpit\Tests\TestCase;
use Illuminate\Http\Request;

class RequestContextTest extends TestCase
{
    /** @test */
    public function it_should_not_return_a_livewire_response(): void
    {
        app()->bind(Request::class, fn() => Request::create(
            '/update/',
            'PUT',
            server:['HTTP_ACCEPT' => 'application/json']
        ));

        $context = app(RequestContext::class)->getContext();

        $this->assertSame('PUT', $context['request']['method']);
        $this->assertArrayNotHasKey('x-livewire', $context['headers']);
    }

}