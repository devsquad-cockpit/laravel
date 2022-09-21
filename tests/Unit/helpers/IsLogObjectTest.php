<?php

namespace Cockpit\Tests\Unit\helpers;

use Cockpit\Tests\TestCase;

class IsLogObjectTest extends TestCase
{
    /** @test */
    public function it_should_ensure_that_the_simple_array_is_not_an_object(): void
    {
        $this->assertFalse(is_log_object(['A simple string here']));
    }

    /** @test */
    public function it_should_ensure_that_an_array_with_key_pair_is_an_object(): void
    {
        $this->assertTrue(is_log_object([
            'item'  => 'A',
            'value' => 'a',
        ]));
    }

    /** @test */
    public function it_should_ensure_that_the_multidimensional_array_will_be_treated_as_object(): void
    {
        $this->assertTrue(is_log_object([
            'item' => [
                'description' => 'A',
                'value'       => 'a',
            ],
        ]));

        $this->assertTrue(is_log_object([
            [
                'description' => 'A',
                'value'       => 'a',
            ],
        ]));
    }

    /** @test */
    public function it_should_ensure_that_the_mixed_array_must_be_an_object(): void
    {
        $this->assertTrue(is_log_object([
            'Is this a entry of the array',
            'item' => [
                'description' => 'A',
                'value'       => 'a',
            ],
        ]));
    }
}