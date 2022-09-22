<?php

namespace Cockpit\Tests\Unit\Exceptions;

use Cockpit\Exceptions\CockpitErrorHandler;
use Cockpit\Tests\TestCase;

class CockpitErrorHandlerTest extends Testcase
{
    /** @test */
    public function it_should_deal_with_ending_slash_at_endpoint_env(): void
    {
        $handler = new CockpitErrorHandler();

        $domain = 'http://localhost/';

        $this->assertSame('http://localhost/webhook', $handler->endpoint($domain));
    }

    /** @test */
    public function it_should_deal_without_ending_slash_at_endpoint_env(): void
    {
        $handler = new CockpitErrorHandler();

        $domain = 'http://localhost';

        $this->assertSame('http://localhost/webhook', $handler->endpoint($domain));
    }
}