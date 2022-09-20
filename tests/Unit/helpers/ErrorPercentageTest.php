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
            ['chunk' => 27, 'total' => 40, 'expected' => 67.5],
            ['chunk' => 12, 'total' => 60, 'expected' => 20.0],
            ['chunk' => 90, 'total' => 100, 'expected' => 90.0],
        ];
    }
}