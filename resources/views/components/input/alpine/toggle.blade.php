@props([
    'label' => null,
    'trigger'
])

<button
    @click="{{ $trigger }} = !{{ $trigger }}"
    type="button"
    role="switch"
    class="ml-4 relative w-10 py-2 px-0 items-center inline-flex rounded-full focus:ring-none bg-dark-primary dark:bg-gray-500" aria-checked="false" aria-labelledby="toggle-label-1">
    <span class="absolute w-6 h-6 rounded-full transition" :class="{ 'bg-primary translate-x-4': {{ $trigger }}, 'bg-gray-400 translate-x-0': !{{ $trigger }} }" aria-hidden="true"></span>
</button>

<p class="inline-flex items-center text-dark-primary dark:text-white text-sm">
    {{ $label ?? $slot }}
</p>
