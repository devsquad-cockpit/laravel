<nav {{ $attributes->class([
    'bg-white dark:bg-dark-primary text-gray-900 dark:text-white',
    'rounded-lg shadow px-2 py-2 space-y-1 w-full',
]) }}>

    <x-cockpit::error.nav.link
        x-on:click.prevent="navigateTo('stackTrace')"
        x-bind:class="currentTab === 'stackTrace' ? 'bg-dark-secondary text-primary' : 'text-white'"
    >
        <svg class="mr-4 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Stacktrace
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link
        x-on:click.prevent="navigateTo('debug')"
        x-bind:class="currentTab === 'debug' ? 'bg-dark-secondary text-primary' : 'text-white'"
    >
        <x-cockpit-icons icon="lightning-bolt" outline class="mr-4 flex-shrink-0" :fill="false"/>
        Debug
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link>
        <x-cockpit-icons app outline class="mr-4 flex-shrink-0" :fill="false"/>
        App
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link>
        <x-cockpit-icons group outline class="mr-4 flex-shrink-0" :fill="false"/>
        User
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link>
        <x-cockpit-icons document outline class="mr-4 flex-shrink-0" :fill="false"/>
        Context
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link>
        <x-cockpit-icons upload outline class="mr-4 flex-shrink-0" :fill="false"/>
        Request
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link>
        <x-cockpit-icons puzzle outline class="mr-4 flex-shrink-0" :fill="false"/>
        Command
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link>
        <x-cockpit-icons cog outline class="mr-4 flex-shrink-0 h-6 w-6" :fill="false"/>
        Job
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link>
        <x-cockpit-icons group outline class="mr-4 flex-shrink-0" :fill="false"/>
        Livewire
    </x-cockpit::error.nav.link>
</nav>
