@props(['title'])

<div {{ $attributes->class(['grid grid-cols-4 gap-6']) }}>
    <div></div>
    <x-cockpit::error.section.title>{{ $title }}</x-cockpit::error.section.title>

    {{ $slot }}
</div>
