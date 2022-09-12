@props([
    'name',
    'id' => null,
    'label' => null,
    'labeless' => null,
    'help' => null,
    'wrapperClass' => null,
    'size' => null,
    'sm' => null,
    'lg' => null
])

@php
    $id = $id ?? $name;
    $label = $label ?? Str::title(Str::replace(['-', '_'], ' ', $name));

    $size = 'md';
    if ($sm) $size = 'sm';
    if ($lg) $size = 'lg';
@endphp

<x-cockpit::input.wrapper class="{{ $wrapperClass }}">
    @unless($labeless)
        <x-cockpit::input.label :for="$name">{{ $label }}</x-cockpit::input.label>
    @endunless

    <select name="{{ $name }}" id="{{ $id }}"
            x-bind:class="{ 'bg-white' : !darkMode, 'bg-transparent' : darkMode}"
            {{ $attributes->class([
                'h-8 text-sm' => $size === 'sm',
                'h-10 text-base' => $size === 'md',
                'h-12 text-lg' => $size === 'lg',
                'appearance-none block w-full py-2 border rounded-md shadow-sm focus:outline-none transition duration-150 ease-in-out text-dark-primary dark:text-white',
                'bg-white dark:bg-dark-secondary border-gray-300 dark:border-gray-500 placeholder-gray-400 focus:border-primary focus:ring-primary' => !$errors->has($name),
                'border-red-500 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red' => $errors->has($name),
            ]) }}>
        {{ $slot }}
    </select>

    @if ($help)
        <x-cockpit::input.help>{{ $help }}</x-cockpit::input.help>
    @endif

    <x-cockpit::input.error-message :for="$name"/>
</x-cockpit::input.wrapper>
