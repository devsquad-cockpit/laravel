@props(['error'])

@php /** @var \Cockpit\Models\Error $error */ @endphp
<nav {{ $attributes->class([
    'bg-white dark:bg-dark-primary text-gray-900 dark:text-white',
    'rounded-lg shadow px-2 py-2 space-y-1 w-full',
]) }}>

    @if($error->trace)
        <x-cockpit::error.nav.link id="stackTrace" icon="lightning-bolt">
            Stacktrace
        </x-cockpit::error.nav.link>
    @endif

    @if($error->debug)
        <x-cockpit::error.nav.link id="debug" icon="lightning-bolt">
            Debug
        </x-cockpit::error.nav.link>
    @endif

    @if($error->app)
        <x-cockpit::error.nav.link id="app" icon="app">
            App
        </x-cockpit::error.nav.link>
    @endif

    @if($error->user)
        <x-cockpit::error.nav.link id="user" icon="group">
            User
        </x-cockpit::error.nav.link>
    @endif

    @if($error->context)
        <x-cockpit::error.nav.link id="context" icon="document">
            Context
        </x-cockpit::error.nav.link>
    @endif

    @if($error->request)
        <x-cockpit::error.nav.link id="request" icon="upload">
            Request
        </x-cockpit::error.nav.link>
    @endif

    @if($error->command)
        <x-cockpit::error.nav.link id="command" icon="puzzle">
            Command
        </x-cockpit::error.nav.link>
    @endif

    @if($error->job)
        <x-cockpit::error.nav.link id="job" icon="cog">
            Job
        </x-cockpit::error.nav.link>
    @endif

    @if($error->livewire)
        <x-cockpit::error.nav.link id="livewire" icon="group">
            Livewire
        </x-cockpit::error.nav.link>
    @endif
</nav>
