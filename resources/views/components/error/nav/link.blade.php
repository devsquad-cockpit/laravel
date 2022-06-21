@props([
    'href'   => '#',
    'active' => null,
    'mobile' => null
])

<a href="" {{ $attributes->class([
    'hover:dark-secondary hover:text-primary group flex items-center px-2 py-2 rounded-md font-thin',
    'dark-secondary text-primary' => $active
]) }}>
    {{ $slot }}
</a>
