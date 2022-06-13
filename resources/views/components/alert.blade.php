@props([
    'outline' => null,

    'primary' => null,
    'gray'    => null,
    'red'     => null,
    'yellow'  => null,
    'green'   => null,
    'blue'    => null,
    'indigo'  => null,
    'purple'  => null,
    'pink'    => null,
    'white'   => null,
])

@php
    $color = 'primary';
    if ($gray) $color = 'gray';
    if ($red) $color = 'red';
    if ($yellow) $color = 'yellow';
    if ($green) $color = 'light-green';
    if ($blue) $color = 'blue';
    if ($indigo) $color = 'indigo';
    if ($purple) $color = 'purple';
    if ($pink) $color = 'pink';
    if ($white) $color = 'white';

    // Text Colors
    $classes = [
        'text-primary-dark bg-primary'         => $color === 'primary' && !$outline,
        'text-gray-700 bg-gray-400'            => $color === 'gray' && !$outline,
        'text-red-700 bg-red-400'              => $color === 'red' && !$outline,
        'text-yellow-700 bg-yellow-400'        => $color === 'yellow' && !$outline,
        'text-light-green-dark bg-light-green' => $color === 'light-green' && !$outline,
        'text-blue-700 bg-blue-400'            => $color === 'blue' && !$outline,
        'text-indigo-700 bg-indigo-400'        => $color === 'indigo' && !$outline,
        'text-purple-700 bg-purple-400'        => $color === 'purple' && !$outline,
        'text-pink-700 bg-pink-400'            => $color === 'pink' && !$outline,
        'text-blank bg-white'                  => $color === 'white' && !$outline,

        'text-primary bg-transparent border border-primary'             => $color === 'primary' && $outline,
        'text-gray-600 bg-transparent border border-gray-600'           => $color === 'gray' && $outline,
        'text-red-600 bg-transparent border border-red-600'             => $color === 'red' && $outline,
        'text-light-green bg-transparent border border-light-green'     => $color === 'light-green' && $outline,
        'text-blue-600 bg-transparent border border-blue-600'           => $color === 'blue' && $outline,
        'text-indigo-600 bg-transparent border border-indigo-600'       => $color === 'indigo' && $outline,
        'text-purple-600 bg-transparent border border-purple-600'       => $color === 'purple' && $outline,
        'text-pink-600 bg-transparent border border-pink-600'           => $color === 'pink' && $outline,
        'text-pink-600 bg-transparent border border-pink-600'           => $color === 'pink' && $outline,
        'text-black dark:text-white bg-transparent border border-white' => $color === 'white' && $outline,
    ];
@endphp

<div class="w-full">
    <div @class(['p-2 rounded-lg shadow-lg sm:p-3'] + $classes)>
        <div class="flex items-center justify-between flex-wrap py-2">
            {{ $slot }}
        </div>
    </div>
</div>
