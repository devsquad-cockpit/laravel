<?php

namespace Cockpit\Tests\Unit\helpers;

use Cockpit\Tests\TestCase;

class ErrorPercentageTest extends TestCase
{
    /** @test */
    public function it_should_return_with_zero_if_one_of_the_numbers_is_equal_to_zero(): void
    {
        $this->assertSame(0, error_percentage(0, 0));
    }

    /**
     * @test
     * @dataProvider data
     */
    public function it_should_calculate_a_percentage_between_numbers(int $chunk, int $total, float $expected): void
    {
        $this->assertSame($expected, error_percentage($chunk, $total));
    }

    public function data(): array
    {
        return [
            [27, 40, 67.5],
            [12, 60, 20.0],
            [90, 100, 90.0]
        ];
    }
}