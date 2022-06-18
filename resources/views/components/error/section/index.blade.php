<div {{ $attributes->merge([
    'class' => 'col-span-4 w-full bg-white dark:bg-dark-primary text-gray-900 dark:text-white rounded-lg shadow space-y-1 w-full'
]) }}
     x-transition:enter="transition ease-in duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
>
    {{ $slot }}
</div>
