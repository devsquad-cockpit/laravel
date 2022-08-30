@props([
    'name',
    'id' => null,
    'nameMax' => null,
    'idMax' => null,
    'label' => null,
    'labeless' => null,
    'value' => null,
    'valueMax' => null,
    'help' => null,
    'wrapperClass' => null
])

@php
    $id = $id ?? $name;
    $label = $label ?? Str::title(Str::replace(['-', '_'], ' ', $name));

    $nameMax = $nameMax ?? $name . '_max';
    $idMax = $idMax ?? $nameMax;

    $value = old($name, $value);
    $valueMax = old($nameMax, $valueMax);
@endphp

<x-cockpit::input.wrapper class="max-w-sm mx-auto {{ $wrapperClass }}"
                          x-data="rangeDatepicker({ minValue: '{{ $value }}', maxValue: '{{ $valueMax }}', minRef: '{{ $id }}', maxRef: '{{ $idMax }}' })">
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" :value="minValue">
    <input type="hidden" name="{{ $nameMax }}" id="{{ $idMax }}" :value="maxValue">

    @unless($labeless)
        <x-cockpit::input.label :for="$name">{{ $label }}</x-cockpit::input.label>
    @endunless

    <div class="flex items-center space-x-3 text-gray-400 font-medium text-sm">
        <div class="relative flex items-center">
            <input type="text" name="{{ $name }}" id="{{ $id }}" x-ref="{{ $id }}" :value="minValue"
                   x-bind:class="{ 'bg-white' : !darkMode, 'bg-transparent' : darkMode}"
                    {{ $attributes->class([
                        'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none transition duration-150 ease-in-out',
                        'sm:text-sm text-black dark:text-white',
                        'bg-transparent dark:bg-dark-secondary border-gray-300 dark:border-gray-500 placeholder-gray-400 focus:border-tw-purple-500 focus:ring-tw-purple-500' => !$errors->has($name),
                        'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red' => $errors->has($name),
                    ]) }}/>

            <x-cockpit-icons icon="calendar" outline class="absolute right-0 mr-3 h-5 w-5"/>
        </div>

        <span class="text-gray-700 dark:text-gray-400">to</span>

        <div class="relative flex items-center">
            <input type="text" name="{{ $nameMax }}" id="{{ $idMax }}" x-ref="{{ $idMax }}" :value="maxValue"
                   x-bind:class="{ 'bg-white' : !darkMode, 'bg-transparent' : darkMode}"
                    {{ $attributes->class([
                        'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none transition duration-150 ease-in-out',
                        'sm:text-sm text-black dark:text-white',
                        'bg-transparent dark:bg-dark-secondary border-gray-300 dark:border-gray-500 placeholder-gray-400 focus:border-tw-purple-500 focus:ring-tw-purple-500' => !$errors->has($nameMax),
                        'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red' => $errors->has($nameMax),
                    ]) }}/>

            <x-cockpit-icons icon="calendar" outline class="absolute right-0 mr-3 h-5 w-5"/>
        </div>
    </div>

    @if ($help)
        <x-cockpit::input.help>{{ $help }}</x-cockpit::input.help>
    @endif

    <x-cockpit::input.error-message :for="$name"/>
    <x-cockpit::input.error-message :for="$nameMax"/>
</x-cockpit::input.wrapper>
