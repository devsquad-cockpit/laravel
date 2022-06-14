@props(['sortBy' => null, 'sortDirection' => null, 'currentSort' => null])

<th scope="col" {{ $attributes->class([
    'text-sm uppercase font-normal tracking-wide',
]) }} x-bind:class="getThColumnClasses($el)">
    <div class="flex items-center space-x-2">
        <span>{{ $slot }}</span>
        @if ($currentSort && $sortBy && $sortDirection)
            @if ($currentSort === $sortBy)
                <x-cockpit-icons icon="chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}" class="h-5 w-5"/>
            @else
                <x-cockpit-icons icon="chevron-up-down" class="h-5 w-5"/>
            @endif
        @endif
    </div>
</th>
