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
    'primary' => null,
    'color' => null,

    // Sizes
    'xs' => null,
    'sm' => null,
    'lg' => null,

    // Rounded
    'rounded' => null,
    'roundedSm' => null,
    'roundedMd' => null,

    // Styles
    'bold' => null
])

@php
    // Colors
    $color = $color ?? 'primary';
    if ($gray) $color = 'gray';
    if ($red) $color = 'red';
    if ($yellow) $color = 'yellow';
    if ($green) $color = 'green';
    if ($blue) $color = 'blue';
    if ($indigo) $color = 'indigo';
    if ($purple) $color = 'purple';

    // Sizes
    $size = 'sm';
    if ($xs) $size = 'xs';
    if ($lg) $size = 'lg';

    // Rounded
    $rounded = 'full';
    if ($roundedSm) $rounded = 'sm';
    if ($roundedMd) $rounded = 'md';
@endphp

<span {{ $attributes->class([
    'inline-flex items-center tracking-wide',
    'font-bold' => $bold,

    // Sizes
    'px-3 py-1 text-xs' => $size === 'xs',
    'px-2.5 py-0.5 text-sm' => $size === 'sm',
    'px-3 py-0.5' => $size === 'lg',

    // Colors
    'bg-primary dark:bg-gray-600 text-dark-primary dark:text-primary' => $color === 'primary',
    'bg-gray-100 text-gray-800' => $color === 'gray',
    'bg-red-400 text-white dark:text-dark-primary' => $color === 'red',
    'bg-yellow-100 text-yellow-800' => $color === 'yellow',
    'bg-green-300 text-green-800' => $color === 'green',
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
