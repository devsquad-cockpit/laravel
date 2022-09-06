@props([
    'name',
    'id' => null,
    'label' => null,
    'labeless' => null,
    'value' => '1',
    'current' => null,
    'wrapperClass' => null
])

@php
    $id = $id ?? $name;
    $label = $label ?? Str::title(Str::replace(['-', '_'], ' ', $name));
    $checked = old($name, $current) == $value;
@endphp

<x-cockpit::input.wrapper class="flex items-center justify-center {{ $wrapperClass }}"
                          x-data="{ value: {{ $checked ? 'true' : 'false' }} }"
                          x-init="$watch('value', function(value) {
                              $refs['{{ $id }}'].dispatchEvent(new Event('change'));
                          })"
                          x-id="['toggle-label']">
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" value="0"/>
    <input type="checkbox" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
           class="hidden" x-model="value" x-ref="{{ $id }}" {{ $attributes }}/>

    @unless($labeless)
        <x-cockpit::input.label :for="$name"
                                x-bind:id="$id('toggle-label')"
                                x-on:click="$refs['{{ $id }}-button'].click(); $refs['{{ $id }}-button'].focus()">
            {{ $label }}
        </x-cockpit::input.label>
    @endunless

    <button x-ref="{{ $id }}-button" x-on:click="value = !value" type="button" role="switch"
            x-bind:aria-checked="value"
            x-bind:aria-labelledby="$id('toggle-label')"
            x-bind:class="value ? 'bg-gray-400 dark:bg-gray-600' : 'bg-gray-600 dark:bg-gray-700'"
            class="ml-4 relative w-10 py-2 px-0 items-center inline-flex rounded-full focus:ring-none">
        <span x-bind:class="value ? 'bg-primary translate-x-4' : 'bg-gray-400 translate-x-0'"
              class="absolute w-6 h-6 rounded-full transition"
              aria-hidden="true"></span>
    </button>

    <x-cockpit::input.error-message :for="$name"/>
</x-cockpit::input.wrapper>
