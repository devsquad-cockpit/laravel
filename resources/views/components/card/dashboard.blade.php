@props(['title', 'total', 'percentage' => null])

<x-cockpit::card class="space-y-2">
    <p class="text-xl tracking-wider font-thin">{{ $title }}</p>
    <div class="flex items-end justify-between">
        <p class="text-3xl text-primary font-bold">{{ number_format($total) }}</p>
        @if ($percentage)
            <x-cockpit::badge sm>{{ $percentage }}% of total</x-cockpit::badge>
        @endif
    </div>
</x-cockpit::card>
