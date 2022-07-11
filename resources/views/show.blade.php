<x-cockpit::app-layout xmlns:x-cockpit="http://www.w3.org/1999/html">
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

    @if ($cockpitError->url)
        <div class="flex justify-between">
            <span class="text-gray-900 dark:text-white text-sm">
                <div class="flex items-center">
                    <x-cockpit-icons icon="link" class="mr-3"/>
                    {{ $cockpitError->url }}
                </div>
            </span>
        </div>
    @endif

    <div class="flex justify-between items-center mt-8">
        <div class="flex gap-7 text-sm">
            <x-cockpit::card.error-status
                    title="Latest Occurrence"
                    value="{{ $cockpitError->occurrence_time }}"
                    description="{{ $cockpitError->occurrence_description }}"
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

        @if ($cockpitError->was_resolved)
            <span class="text-green-700 font-bold flex items-center">
                Resolved <x-cockpit-icons check-circle outline class="text-green-700 ml-3"/>
            </span>
        @else
            <form action="{{ route('cockpit.resolve', $cockpitError->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <x-cockpit::button green>
                    Mark as Resolved
                </x-cockpit::button>
            </form>
        @endif
    </div>

    <div class="grid grid-cols-5 gap-4 mt-8" x-data="tab()">
        <x-cockpit::error.nav :error="$cockpitError"/>

        @if ($cockpitError->trace->isNotEmpty())
            <x-cockpit::error.stacktrace x-show="isActive('stackTrace')"
                                         x-data="stackTrace({{ json_encode($cockpitError->trace) }})"/>
        @endif

        <x-cockpit::error.debug x-show="isActive('debug')"/>

        @if ($cockpitError->app)
            <x-cockpit::error.app x-show="isActive('app')" :error="$cockpitError"/>
        @endif

        @if ($cockpitError->user)
            <x-cockpit::error.user x-show="isActive('user')" :error="$cockpitError"/>
        @endif

        @if ($cockpitError->context->isNotEmpty())
            <x-cockpit::error.context x-show="isActive('context')" :error="$cockpitError"/>
        @endif

        <x-cockpit::error.request x-show="isActive('request')"/>

        @if ($cockpitError->command)
            <x-cockpit::error.command x-show="isActive('command')" :error="$cockpitError"/>
        @endif

        @if ($cockpitError->job)
            <x-cockpit::error.job x-show="isActive('job')" :error="$cockpitError"/>
        @endif

        @if ($cockpitError->livewire)
            <x-cockpit::error.livewire x-show="isActive('livewire')" :error="$cockpitError"/>
        @endif
    </div>
</x-cockpit::app-layout>
