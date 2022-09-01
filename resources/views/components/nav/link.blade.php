@props(['href' => '#', 'active' => null, 'mobile' => null, 'noBackground' => null, 'noPadding' => null])

<a href="{{ $href }}" {{ $attributes->class([
    'inline-flex items-center rounded-lg text-sm font-medium' => !$mobile,
    'py-2 px-3' => !$noPadding && !$mobile,
    'block text-base font-medium' => $mobile,
    'pl-3 pr-4 py-2' => !$noPadding && $mobile,
    'text-white' => $active,
    'dark:text-white' => $active && !$mobile,
    'dark:text-primary' => $active && $mobile,
    'text-dark dark:text-white hover:text-white dark:hover:text-black' => !$active,
    'hover:bg-dark-primary dark:hover:bg-primary transition' => !$noBackground,
    'bg-dark-primary text-white dark:bg-primary dark:text-black' => !$noBackground && $active,
    'bg-transparent' => $noBackground || !$active,
]) }}>
    {{ $slot }}
</a>
