<?php

namespace Cockpit\Tests\Unit;

use Cockpit\Cockpit;
use Cockpit\Tests\TestCase;
use Illuminate\Http\Request;

class CockpitTest extends TestCase
{
    /** @test */
    public function it_should_return_default_value_for_authentication_if_authUsing_is_not_be_set(): void
    {
        $this->assertSame(app()->isLocal(), Cockpit::check(new Request()));
    }

    /**
     * @test
     * @dataProvider data
     */
    public function it_should_be_check_auth_with_success(bool $value): void
    {
        $cockpit = app(Cockpit::class);
        $cockpit->auth(function () use ($value) {
            return $value;
        });

        $this->assertSame($value, $cockpit->check(new Request));
    }

    private function data(): array
    {
        return [
            [true],
            [false],
        ];
    }
}
