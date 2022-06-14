@props(['sortBy' => null, 'sortDirection' => null, 'currentSort' => null])

@php
    $sortDirection = $sortDirection ?? request()->get('sortDirection', 'asc');
    $currentSort = $currentSort ?? request()->get('sortBy');
    $sortDirection = $currentSort === $sortBy ? ($sortDirection === 'asc' ? 'desc' : 'asc') : 'asc';
@endphp

<th scope="col" {{ $attributes->class([
    'text-sm uppercase font-normal tracking-wide',
]) }} x-bind:class="getThColumnClasses($el)">
    <div class="flex items-center space-x-2">
        <span>{{ $slot }}</span>
        @if ($sortBy)
            @if ($currentSort === $sortBy)
                <x-cockpit-icons icon="chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}" class="h-5 w-5"/>
            @else
                <x-cockpit-icons icon="chevron-up-down" class="h-5 w-5"/>
            @endif
        @endif
    </div>
</th>
