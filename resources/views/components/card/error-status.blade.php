@props(['title', 'value', 'description' => null])

<div class="text-gray-900 dark:text-white space-y-2">
    <p class="text-xl tracking-wider font-thin">{{ $title }}</p>
    <div class="flex items-baseline gap-3">
        <p class="text-2xl text-primary font-bold">
            {{ is_numeric($value) ? number_format($value) : $value }}
        </p>

        @if ($description)
            <span class="dark:text-white text-thin">{{ $description }}</span>
        @endif
    </div>
</div>
