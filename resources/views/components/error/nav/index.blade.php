<nav {{ $attributes->class([
    'bg-white dark:bg-dark-primary text-gray-900 dark:text-white',
    'rounded-lg shadow px-2 py-2 space-y-1 w-full',
]) }}>

    <x-cockpit::error.nav.link id="stackTrace" icon="lightning-bolt">
        Stacktrace
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link id="debug" icon="lightning-bolt">
        Debug
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link id="app" icon="app">
        App
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link id="user" icon="group">
        User
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link id="context" icon="document">
        Context
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link id="request" icon="upload">
        Request
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link id="command" icon="puzzle">
        Command
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link id="job" icon="cog">
        Job
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link id="livewire" icon="group">
        Livewire
    </x-cockpit::error.nav.link>
</nav>
