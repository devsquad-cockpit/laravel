<div {{ $attributes->merge([
    'class' => 'col-span-4 w-full bg-white dark:bg-dark-primary text-gray-900 dark:text-white rounded-lg shadow space-y-1 w-full'
]) }}
     x-transition:enter="transition ease-out duration-150"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-150"
     x-transition:leave-end="opacity-0"
>
    {{ $slot }}
</div>
