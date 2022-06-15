@props([
    'type' => 'text',
    'name',
    'id' => null,
    'label' => null,
    'labeless' => null,
    'value' => null,
    'help' => null,
    'iconLeft' => null,
    'iconRight' => null,
    'wrapperClass' => null
])

@php
    $id = $id ?? $name;
    $label = $label ?? Str::title(Str::replace(['-', '_'], ' ', $name));
    $value = old($name, $value);
@endphp

<x-cockpit::input.wrapper class="{{ $wrapperClass }}">
    @unless($labeless)
        <x-cockpit::input.label :for="$name">{{ $label }}</x-cockpit::input.label>
    @endunless

    @if ($iconLeft || $iconRight)
        <div class="relative flex items-center">
            @if ($iconLeft)
                <x-cockpit-icons icon="{{ $iconLeft }}" class="absolute ml-3 h-5 w-5"/>
            @endif
            @endif

            <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
                    {{ $attributes->class([
                        'pl-10' => $iconLeft,
                        'pr-10' => $iconRight,
                        'pl-3' => !$iconLeft,
                        'pr-3' => !$iconRight,
                        'appearance-none block w-full py-2 border rounded-md shadow-sm focus:outline-none transition duration-150 ease-in-out',
                        'sm:text-sm text-black dark:text-white',
                        'bg-white dark:bg-dark-secondary border-gray-300 placeholder-gray-400 focus:border-tw-purple-500 focus:ring-tw-purple-500' => !$errors->has($name),
                        'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red' => $errors->has($name),
                    ]) }}/>

            @if ($iconLeft || $iconRight)
                @if ($iconRight)
                    <x-cockpit-icons icon="{{ $iconRight }}" class="absolute right-0 mr-3 h-5 w-5"/>
                @endif
        </div>
    @endif

    @if ($help)
        <x-cockpit::input.help>{{ $help }}</x-cockpit::input.help>
    @endif

    <x-cockpit::input.error-message :for="$name"/>
</x-cockpit::input.wrapper>
