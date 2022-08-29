@props([
    'id',
    'icon'
])

<a href="#"
   id="link-{{ $id }}"
   x-on:click.prevent="navigateTo('{{ $id }}')"
   x-bind:class="isActive('{{ $id }}') ? 'bg-gray-400 dark:bg-dark-secondary text-white dark:text-primary' : 'text-gray-700 dark:text-white'"
        {{ $attributes->class([
             'hover:text-gray-500 dark:hover:dark-secondary dark:hover:text-primary group flex items-center px-2 py-2 rounded-md font-thin',
         ]) }}>
    <x-cockpit-icons :icon="$icon" outline class="mr-4 flex-shrink-0" :fill="false"/>
    {{ $slot }}
</a>
