@props([
    'name',
    'id' => null,
    'label' => null,
    'labeless' => null,
    'help' => null,
    'wrapperClass' => null
])

@php
    $id = $id ?? $name;
    $label = $label ?? Str::title(Str::replace(['-', '_'], ' ', $name));
@endphp

<x-cockpit::input.wrapper class="max-w-sm mx-auto {{ $wrapperClass }}" x-data="datepicker({ ref: '{{ $id }}' })">
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" :value="value">

    @unless($labeless)
        <x-cockpit::input.label :for="$name">{{ $label }}</x-cockpit::input.label>
    @endunless

    <div class="relative flex items-center">
        <input type="text" name="{{ $name }}" id="{{ $id }}" x-ref="{{ $id }}"
                {{ $attributes->class([
                    'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none transition duration-150 ease-in-out',
                    'sm:text-sm text-black dark:text-white',
                    'bg-white dark:bg-dark-secondary border-gray-300 placeholder-gray-400 focus:border-tw-purple-500 focus:ring-tw-purple-500' => !$errors->has($name),
                    'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red' => $errors->has($name),
                ]) }}/>

        <x-cockpit-icons icon="calendar" class="absolute right-0 mr-3 h-5 w-5"/>
    </div>

    @if ($help)
        <x-cockpit::input.help>{{ $help }}</x-cockpit::input.help>
    @endif

    <x-cockpit::input.error-message :for="$name"/>
</x-cockpit::input.wrapper>
