@props([
    'handler',
])

<div class="relative inline-block text-left" x-data="{ open: false }">
    <button x-on:click="open = !open" class="flex items-center">
        {{ $handler }}
    </button>
    <div
            x-show="open"
            @click.outside="open = false"
            x-transition:enter="transition ease-out duration-75"
            x-transition:enter-start="transform opacity-0 scale-90"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute right-0 z-10 mt-8 w-48 origin-top-right rounded-md bg-white dark:bg-dark-primary shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
        <div class="space-y-3 py-3" role="none">
            {{ $slot }}
        </div>
    </div>
</div>
