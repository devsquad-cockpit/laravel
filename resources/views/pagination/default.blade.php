@if ($paginator->hasPages())
    @php
        $perPageOptions = [
            10 => 10,
            25 => 25,
            50 => 50,
            100 => 100,
        ];
    @endphp

    <div class="flex items-center justify-between bg-gray-300 dark:bg-dark-primary mt-3 px-6 py-4">
        <div class="flex items-center space-x-4">
            <x-cockpit::input.select name="perPage" labeless x-data="{}" x-on:change="perPage($el.value)">
                @foreach ($perPageOptions as $perPageOption)
                    <option value="{{ $perPageOption }}"
                            @if (request()->get('perPage') == $perPageOption) selected @endif>
                        Show {{ $perPageOption }} items/page
                    </option>
                @endforeach
            </x-cockpit::input.select>

            <p class="text-sm text-dark-primary dark:text-white leading-5">
                {!! __('Showing') !!}
                @if ($paginator->firstItem())
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('of') !!}
                <span class="font-medium">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>
        </div>

        <div class="flex items-center space-x-4">
            @if ($paginator->onFirstPage())
                <x-cockpit::pagination.button>
                    <x-cockpit-icons icon="arrow-left" class="h-4 w-4 mr-2 text-white dark:text-dark-primary"/>
                    {!! __('Previous') !!}
                </x-cockpit::pagination.button>
            @else
                <x-cockpit::pagination.button :href="$paginator->previousPageUrl()">
                    <x-cockpit-icons icon="arrow-left" class="h-4 w-4 mr-2 text-white dark:text-dark-primary"/>
                    {!! __('Previous') !!}
                </x-cockpit::pagination.button>
            @endif

            @if ($paginator->hasMorePages())
                <x-cockpit::pagination.button :href="$paginator->nextPageUrl()">
                    {!! __('Next') !!}
                    <x-cockpit-icons icon="arrow-right" class="h-4 w-4 ml-2 text-white dark:text-dark-primary"/>
                </x-cockpit::pagination.button>
            @else
                <x-cockpit::pagination.button>
                    {!! __('Next') !!}
                    <x-cockpit-icons icon="arrow-right" class="h-4 w-4 ml-2 text-white dark:text-dark-primary"/>
                </x-cockpit::pagination.button>
            @endif
        </div>
    </div>
@endif
