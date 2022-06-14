@props([
    'name',
    'id' => null,
    'label' => null,
    'labeless' => null,
    'wrapperClass' => null
])

@php
    $id = $id ?? $name;
    $label = $label ?? Str::title(Str::replace(['-', '_'], ' ', $name));
@endphp

<x-cockpit::input.wrapper class="flex items-center justify-center {{ $wrapperClass }}" x-data="{ value: false }"
                          x-id="['toggle-label']">
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" :value="value">

    @unless($labeless)
        <x-cockpit::input.label :for="$name"
                                x-bind:id="$id('toggle-label')"
                                x-on:click="$refs['{{ $id }}'].click(); $refs['{{ $id }}'].focus()">
            {{ $label }}
        </x-cockpit::input.label>
    @endunless

    <button x-ref="{{ $id }}" x-on:click="value = ! value" type="button" role="switch" x-bind:aria-checked="value"
            x-bind:aria-labelledby="$id('toggle-label')"
            x-bind:class="value ? 'bg-gray-500' : 'bg-dark-primary'"
            class="ml-4 relative w-14 py-2 px-0 items-center inline-flex rounded-full">
        <span x-bind:class="value ? 'bg-primary translate-x-8' : 'bg-gray-400 translate-x-0'"
              class="absolute w-6 h-6 rounded-full transition"
              aria-hidden="true"></span>
    </button>

    <x-cockpit::input.error-message :for="$name"/>
</x-cockpit::input.wrapper>
