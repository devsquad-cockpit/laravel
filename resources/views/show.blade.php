<x-cockpit::app-layout>
    @php
        /** @var \Cockpit\Models\Error $cockpitError */
    @endphp
    <a href="{{ route('cockpit.index') }}"
       class="flex items-center text-gray-900 dark:text-white text-sm cursor-pointer">
        <x-cockpit-icons icon="arrow-left" class="mr-3"/>
        Back
    </a>

    <x-cockpit::error.error-title>
        {{ $cockpitError->exception }}: {{ $cockpitError->message }}
    </x-cockpit::error.error-title>

    <span class="text-gray-900 dark:text-white text-sm">
        <div class="flex items-center">
            <x-cockpit-icons icon="link" class="mr-3"/>
            {{ $cockpitError->url }}
        </div>
    </span>

    <div class="grid grid-cols-4 gap-3 items-center mt-6">
        <x-cockpit::card.error-status
            title="Latest Occurrence"
            value="{{ $cockpitError->last_occurrence_at->diffForHumans() }}"
            {{--            description="mins ago"--}}
        />

        <x-cockpit::card.error-status
            title="First Occurrence"
            :value="$cockpitError->created_at->toFormattedDateString()"
        />

        <x-cockpit::card.error-status
            title="# of occurrences"
            :value="$cockpitError->occurrences"
        />

        <x-cockpit::card.error-status
            title="Affected Users"
            :value="$cockpitError->affected_users"
        />
    </div>

    <div class="grid grid-cols-5 gap-4 mt-8" x-data="tab('stackTrace')">
        <x-cockpit::error.nav/>

        <x-cockpit::error.stack-trace
            x-show="currentTab === 'stackTrace'"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            x-data="stackTrace({{ json_encode($cockpitError->trace) }})"
        />

        <x-cockpit::error.debug
            x-show="currentTab === 'debug'"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
        />

    </div>
</x-cockpit::app-layout>
