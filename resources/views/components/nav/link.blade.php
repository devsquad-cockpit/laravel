@props(['href' => '#', 'active' => null, 'noBorder' => null])

<a href="{{ $href }}" {{ $attributes->class([
    'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium',
    'text-gray-900 dark:text-white' => $active,
    'text-gray-500 dark:text-white hover:text-gray-700 dark:hover:text-primary' => !$active,
    'hover:border-gray-300 dark:hover:border-primary' => !$noBorder,
    'border-indigo-500 dark:border-primary' => !$noBorder && $active,
    'border-transparent' => $noBorder || !$active,
]) }}>
    {{ $slot }}
</a>
