@props([
    'id',
    'icon'
])

<a href="#"
   id="link-{{ $id }}"
   x-on:click.prevent="navigateTo('{{ $id }}')"
   x-bind:class="isActive('{{ $id }}') ? 'bg-gray-200 dark:bg-dark-secondary text-gray-600 dark:text-primary' : 'text-gray-700 dark:text-white'"
        {{ $attributes->class([
             'dark:hover:dark-secondary dark:hover:text-primary group flex items-center px-2 py-2 rounded-md font-thin transition',
         ]) }}>
    <div class="inline-flex" x-show="!errorDetailLayoutMinimal">
        <x-cockpit-icons :icon="$icon" class="mr-4 flex-shrink-0" outline :fill="false" />
        {{ $slot }}
    </div>
    <div class="inline-flex" x-show="errorDetailLayoutMinimal">
        <x-cockpit-icons :icon="$icon" class="mx-2 flex justify-center" outline :fill="false" x-tooltip="{{ $slot }}" />
    </div>
</a>
