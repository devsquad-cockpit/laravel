@props(['href' => '#', 'active' => null, 'noBackground' => null, 'mobile' => null])

<a href="{{ $href }}" {{ $attributes->class([
    'inline-flex items-center py-2 px-3 rounded-lg text-sm font-medium' => !$mobile,
    'block pl-3 pr-4 py-2 text-base font-medium' => $mobile,
    'text-gray-900' => $active,
    'dark:text-white' => $active && !$mobile,
    'dark:text-primary' => $active && $mobile,
    'text-gray-500 dark:text-white hover:text-gray-700 dark:hover:text-black' => !$active,
    'hover:bg-gray-300 dark:hover:bg-primary' => !$noBackground,
    'bg-indigo-500 text-white dark:bg-primary dark:text-black' => !$noBackground && $active,
    'bg-transparent' => $noBackground || !$active,
]) }}>
    {{ $slot }}
</a>
