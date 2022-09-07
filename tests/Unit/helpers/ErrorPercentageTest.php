<?php

it('should return with zero if one of the numbers is equal to zero', function () {
    expect(error_percentage(0, 0))
        ->toBe(0);
});

it('should calculate a percentage between numbers', function ($chunk, $total, $expected) {
    expect(error_percentage($chunk, $total))
        ->toBe($expected);
})->with([
    [27, 40, 67.5],
    [12, 60, 20.0],
    [90, 100, 90.0]
]);
