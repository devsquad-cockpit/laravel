@props([
    // Colors
    'gray' => null,
    'red' => null,
    'yellow' => null,
    'green' => null,
    'blue' => null,
    'indigo' => null,
    'purple' => null,
    'pink' => null,

    // Sizes
    'sm' => null,
    'lg' => null,

    // Rounded
    'rounded' => null,
    'roundedSm' => null,
    'roundedMd' => null,
])

@php
    // Colors
    $color = 'gray';
    if ($red) $color = 'red';
    if ($yellow) $color = 'yellow';
    if ($green) $color = 'green';
    if ($blue) $color = 'blue';
    if ($indigo) $color = 'indigo';
    if ($purple) $color = 'purple';

    // Sizes
    $size = 'sm';
    if ($lg) $size = 'lg';

    // Rounded
    $rounded = 'full';
    if ($roundedSm) $rounded = 'sm';
    if ($roundedMd) $rounded = 'md';
@endphp

<span {{ $attributes->class([
    'inline-flex items-center font-medium',

    // Sizes
    'px-2.5 py-0.5' => $size === 'sm',
    'px-3 py-0.5' => $size === 'lg',

    // Colors
    'bg-gray-100 text-gray-800' => $color === 'gray',
    'bg-red-100 text-red-800' => $color === 'red',
    'bg-yellow-100 text-yellow-800' => $color === 'yellow',
    'bg-green-100 text-green-800' => $color === 'green',
    'bg-blue-100 text-blue-800' => $color === 'blue',
    'bg-indigo-100 text-indigo-800' => $color === 'indigo',
    'bg-purple-100 text-purple-800' => $color === 'purple',
    'bg-pink-100 text-pink-800' => $color === 'pink',

    // Rounded
    'rounded-full' => $rounded === 'full',
    'rounded' => $rounded === 'sm',
    'rounded-md' => $rounded === 'md',
]) }}>
    {{ $slot }}
</span>
