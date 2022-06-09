@props(['href' => '#', 'active' => null, 'noBorder' => null, 'mobile' => null])

<a href="{{ $href }}" {{ $attributes->class([
    'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium' => !$mobile,
    'block pl-3 pr-4 py-2 border-l-4 text-base font-medium' => $mobile,
    'text-gray-900' => $active,
    'dark:text-white' => $active && !$mobile,
    'dark:text-primary' => $active && $mobile,
    'text-gray-500 dark:text-white hover:text-gray-700 dark:hover:text-primary' => !$active,
    'hover:border-gray-300 dark:hover:border-primary' => !$noBorder,
    'border-indigo-500 dark:border-primary' => !$noBorder && $active,
    'border-transparent' => $noBorder || !$active,
]) }}>
    {{ $slot }}
</a>
