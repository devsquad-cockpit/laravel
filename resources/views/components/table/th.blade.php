@props(['sortBy' => null, 'sortDirection' => null, 'currentSort' => null, 'default' => null])

@php
    $sortDirection = $sortDirection ?? request()->get('sortDirection', 'asc');
    $currentSort = $currentSort ?? request()->get('sortBy', $default ? $sortBy : null);
    $sortDirection = $currentSort === $sortBy ? ($sortDirection === 'asc' ? 'desc' : 'asc') : 'desc';
@endphp

<th scope="col" {{ $attributes->class([
    'text-sm uppercase font-normal tracking-wide',
    'cursor-pointer' => $sortBy,
]) }}
@if ($sortBy) x-on:click="sortBy(@js($sortBy), @js($sortDirection))" @endif
    x-bind:class="getThColumnClasses($el)">
    <div class="flex items-center space-x-2">
        <div>{{ $slot }}</div>
        @if ($sortBy)
            <div>
                @if ($currentSort === $sortBy)
                    <x-cockpit-icons icon="chevron-{{ $sortDirection === 'asc' ? 'down' : 'up' }}" class="h-5 w-5"/>
                @else
                    <x-cockpit-icons icon="chevron-up-down" class="h-5 w-5"/>
                @endif
            </div>
        @endif
    </div>
</th>
