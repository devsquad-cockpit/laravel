@props([
    'href'   => '#',
    'active' => null,
    'mobile' => null
])

<a href="" {{ $attributes->class([
    'hover:bg-dark hover:text-primary group flex items-center px-2 py-2 rounded-md font-thin'
]) }}>
    {{ $slot }}
</a>
