<nav {{ $attributes->class([
    'bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-lg shadow px-2 py-2 space-y-1 w-full'
]) }}>

    <x-cockpit::error.nav.link
        x-on:click.prevent="navigateTo('stackTrace')"
        x-bind:class="currentTab === 'stackTrace' ? '!text-primary' : 'text-white'"
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
        x-bind:class="currentTab === 'debug' ? '!text-primary' : 'text-white'"
    >
        <x-cockpit-icons icon="lightning-bolt" outline class="mr-4 flex-shrink-0"/>
        Debug
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link>
        <x-cockpit-icons icon="app" outline class="mr-4 flex-shrink-0"/>
        App
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link>
        <x-cockpit-icons icon="group" outline class="mr-4 flex-shrink-0"/>
        User
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link>
        <x-cockpit-icons icon="document" outline class="mr-4 flex-shrink-0"/>
        Context
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link>
        <x-cockpit-icons icon="upload" outline class="mr-4 flex-shrink-0"/>
        Request
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link>
        <x-cockpit-icons icon="puzzle" outline class="mr-4 flex-shrink-0"/>
        Command
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link>
        <x-cockpit-icons icon="cog" outline class="mr-4 flex-shrink-0 h-6 w-6"/>
        Job
    </x-cockpit::error.nav.link>

    <x-cockpit::error.nav.link>
        <x-cockpit-icons icon="group" outline class="mr-4 flex-shrink-0"/>
        Livewire
    </x-cockpit::error.nav.link>
</nav>
