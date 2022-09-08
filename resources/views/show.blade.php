<x-cockpit::app-layout xmlns:x-cockpit="http://www.w3.org/1999/html">
    @php /** @var \Cockpit\Models\Error $cockpitError */ @endphp
    @php($occurrence = $cockpitError->latestOccurrence)

    <div class="inline-flex">
        <a href="{{ route('cockpit.index') }}"
           class="flex items-center text-dark-primary dark:text-white text-sm">
            <x-cockpit-icons icon="arrow-left" class="mr-2"/>
            {{ __('Back') }}
        </a>
    </div>

    <x-cockpit::error.error-title>
        {{ $cockpitError->exception }}: {{ $cockpitError->message }}
    </x-cockpit::error.error-title>

    @if ($occurrence->url)
        <div class="flex justify-between">
            <span class="text-dark-primary dark:text-white text-sm">
                <div class="flex items-center">
                    <x-cockpit-icons icon="link" class="mr-3"/>
                    {{ $occurrence->url }}
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
                    :value="$cockpitError->occurrences_count"
            />

            <x-cockpit::card.error-status
                    title="Affected Users"
                    :value="$cockpitError->affected_users_count"
            />
        </div>

        @if ($cockpitError->was_resolved)
            <span class="text-green-500 font-bold flex items-center">
                Resolved <x-cockpit-icons check-circle outline class="text-green-500 ml-3"/>
            </span>
        @else
            <form action="{{ route('cockpit.resolve', $cockpitError->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <x-cockpit::button green xs>
                    Mark as Resolved
                </x-cockpit::button>
            </form>
        @endif
    </div>

    <div class="flex justify-end items-center mt-4">
        <x-cockpit::input.toggle.error-view-mode/>
    </div>

    <div class="mt-4" x-bind:class="{
            'grid grid-cols-5 gap-4 mb-12' : !errorLayoutNavBar,
            'mb-12' : errorLayoutNavBar
        }" x-data="tab()">
        <x-cockpit::error.nav :occurrence="$occurrence"/>

        @if ($occurrence->trace->isNotEmpty())
            <x-cockpit::error.stacktrace x-show="isActive('stackTrace')"
                                         x-data="stackTrace({{ json_encode($occurrence->trace) }})"/>
        @endif

        @if ($occurrence->debug->isNotEmpty())
            <x-cockpit::error.debug x-show="isActive('debug')" :occurrence="$occurrence"/>
        @endif

        @if ($occurrence->app->isNotEmpty())
            <x-cockpit::error.app x-show="isActive('app')" :occurrence="$occurrence"/>
        @endif

        @if ($occurrence->user->isNotEmpty())
            <x-cockpit::error.user x-show="isActive('user')" :occurrence="$occurrence"/>
        @endif

        @if ($occurrence->context->isNotEmpty())
            <x-cockpit::error.context x-show="isActive('context')" :occurrence="$occurrence"/>
        @endif

        @if ($occurrence->request->isNotEmpty())
            <x-cockpit::error.request x-show="isActive('request')"
                                      :occurrence="$occurrence"/>
        @endif

        @if ($occurrence->command->isNotEmpty())
            <x-cockpit::error.command x-show="isActive('command')" :occurrence="$occurrence"/>
        @endif

        @if ($occurrence->job->isNotEmpty())
            <x-cockpit::error.job x-show="isActive('job')" :occurrence="$occurrence"/>
        @endif

        @if ($occurrence->livewire->isNotEmpty())
            <x-cockpit::error.livewire x-show="isActive('livewire')" :occurrence="$occurrence"/>
        @endif

        @if ($occurrence->environment->isNotEmpty())
            <x-cockpit::error.environment x-show="isActive('environment')" :occurrence="$occurrence"/>
        @endif
    </div>
</x-cockpit::app-layout>
