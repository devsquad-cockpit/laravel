@props([
    'type' => 'submit',
    'href' => null,

    // Sizes
    'xs' => null,
    'sm' => null,
    'md' => null,
    'lg' => null,
    'xl' => null,

    // Colors
    'gray' => null,
    'red' => null,
    'yellow' => null,
    'green' => null,
    'blue' => null,
    'indigo' => null,
    'purple' => null,
    'pink' => null,
    'white' => null,
])

@php
    // Sizes
    $size = 'md';
    if ($xs) $size = 'xs';
    if ($sm) $size = 'sm';
    if ($lg) $size = 'lg';
    if ($xl) $size = 'xl';

    // Colors
    $color = 'gray';
    if ($red) $color = 'red';
    if ($yellow) $color = 'yellow';
    if ($green) $color = 'green';
    if ($blue) $color = 'blue';
    if ($indigo) $color = 'indigo';
    if ($purple) $color = 'purple';
    if ($white) $color = 'white';

    $class = [
        'inline-flex items-center border border-transparent font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2',

        // Sizes
        'px-2.5 py-1.5 text-xs rounded'          => $size === 'xs',
        'px-3 py-2 text-sm rounded-md leading-4' => $size === 'sm',
        'px-4 py-2 text-sm rounded-md'           => $size === 'md',
        'px-4 py-2 text-base rounded-md'         => $size === 'lg',
        'px-6 py-3 text-base rounded-md'         => $size === 'xl',

        // Colors
        'bg-gray-500 hover:bg-gray-600 text-gray-800 hover:text-gray-900 focus:ring-gray-500'                                      => $color === 'gray',
        'bg-red-500 hover:bg-red-600 text-white dark:text-red-800 dark:hover:text-red-900 focus:ring-red-500'                      => $color === 'red',
        'bg-yellow-500 hover:bg-yellow-600 text-yellow-800 hover:text-yellow-900 focus:ring-yellow-500'                            => $color === 'yellow',
        'bg-green-500 hover:bg-green-600 text-white dark:text-green-800 dark:hover:text-green-900 focus:ring-green-500'            => $color === 'green',
        'bg-blue-500 hover:bg-blue-600 text-blue-800 hover:text-blue-900 focus:ring-blue-500'                                      => $color === 'blue',
        'bg-indigo-500 hover:bg-indigo-600 text-indigo-800 hover:text-indigo-900 focus:ring-indigo-500'                            => $color === 'indigo',
        'bg-purple-500 hover:bg-purple-600 text-purple-800 hover:text-purple-900 focus:ring-purple-500'                            => $color === 'purple',
        'bg-pink-500 hover:bg-pink-600 text-pink-800 hover:text-pink-900 focus:ring-pink-500'                                      => $color === 'pink',
        'bg-gray-900 hover:bg-gray-600 dark:bg-white dark:hover:bg-gray-200 text-white dark:text-dark-primary focus:ring-gray-500' => $color === 'white',
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($class) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($class) }}>
        {{ $slot }}
    </button>
@endif
