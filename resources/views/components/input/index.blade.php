@props(['type' => 'text', 'name', 'id' => null, 'label' => null, 'labeless' => null, 'help' => null])

@php
    $id = $id ?? $name;
    $label = Str::title(Str::replace(['-', '_'], ' ', $name));
@endphp

<x-cockpit::input.wrapper>
    @unless($labeless)
        <x-cockpit::input.label :for="$name">{{ $label }}</x-cockpit::input.label>
    @endunless

    <input type="text" name="{{ $name }}" id="{{ $id }}"
            {{ $attributes->class([
                'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none sm:text-sm transition duration-150 ease-in-out',
                'bg-white dark:bg-dark-secondary border-gray-300 placeholder-gray-400 focus:border-tw-purple-500 focus:ring-tw-purple-500' => !$errors->has($name),
                'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red' => $errors->has($name),
            ]) }}/>

    @if ($help)
        <x-cockpit::input.help>{{ $help }}</x-cockpit::input.help>
    @endif

    <x-cockpit::input.error-message :for="$name"/>
</x-cockpit::input.wrapper>
