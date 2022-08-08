<?php

it('should ensure that the simple array is not an object', function () {
    $simpleArray = ['A simple string here'];

    expect(is_log_object($simpleArray))
        ->toBeFalsy();
});

it('should ensure that an array with key pair is an object', function () {
    $array = [
        'item'  => 'A',
        'value' => 'a',
    ];

    expect(is_log_object($array))
        ->toBeTruthy();
});

it('should ensure that the multidimensional array will be treated as object', function () {
    $array = [
        'item' => [
            'description' => 'A',
            'value'       => 'a',
        ],
    ];

    expect(is_log_object($array))
        ->toBeTruthy();

    $array = [
        [
            'description' => 'A',
            'value'       => 'a',
        ],
    ];

    expect(is_log_object($array))
        ->toBeTruthy();
});

it('should ensure that the mixed array must be an object', function () {
    $array = [
        'Is this a entry of the array',
        'item' => [
            'description' => 'A',
            'value'       => 'a',
        ],
    ];

    expect(is_log_object($array))
        ->toBeTruthy();
});
