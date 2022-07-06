@props([
    'id',
    'icon'
])

<a href="#"
   id="link-{{ $id }}"
   x-on:click.prevent="navigateTo('{{ $id }}')"
   x-bind:class="isActive('{{ $id }}') ? 'bg-dark-secondary text-primary' : 'text-white'"
        {{ $attributes->class([
             'hover:dark-secondary hover:text-primary group flex items-center px-2 py-2 rounded-md font-thin',
         ]) }}>
    <x-cockpit-icons :icon="$icon" outline class="mr-4 flex-shrink-0" :fill="false"/>
    {{ $slot }}
</a>
