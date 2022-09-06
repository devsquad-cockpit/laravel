<nav class="bg-white dark:bg-dark-primary shadow-sm dark:border-b dark:border-gray-900" x-data="{ open: false }">
    <div class="relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <x-cockpit::logo />
                    </div>
                    <div class="-my-px ml-6 flex items-center space-x-8">
                        <span class="border-l border-black dark:border-gray-400 text-black dark:text-gray-400 pl-4">{{ Str::title(config('app.env')) }}</span>
                    </div>
                    {{-- Todo: temporary element! Use this to switch between dark and white to see the diferences --}}
                    <div class="-my-px ml-6 flex items-center space-x-8">
                        <p class="text-xs text-dark-primary dark:text-white">Light / Dark Mode</p>
                        <button @click="darkMode = !darkMode" type="button" role="switch" class="ml-4 relative w-10 py-2 px-0 items-center inline-flex rounded-full focus:ring-none bg-dark-primary" aria-checked="false" aria-labelledby="toggle-label-1">
                            <span class="absolute w-6 h-6 rounded-full transition bg-gray-400 translate-x-0" aria-hidden="true"></span>
                        </button>
                    </div>
                    {{-- Todo: temporary element! Use to change the error details layout disposition --}}
                    <div class="-my-px ml-6 flex items-center space-x-8">
                        <p class="text-xs text-dark-primary dark:text-white">Error Detail Layout Minimal</p>
                        <button @click="errorDetailLayoutMinimal = !errorDetailLayoutMinimal" type="button" role="switch" class="ml-4 relative w-10 py-2 px-0 items-center inline-flex rounded-full focus:ring-none bg-dark-primary" aria-checked="false" aria-labelledby="toggle-label-1">
                            <span class="absolute w-6 h-6 rounded-full transition bg-gray-400 translate-x-0" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-3">
                    <x-cockpit::nav.link :href="route('cockpit.index')" active="{{ request()->routeIs('cockpit.index') || request()->routeIs('cockpit.show') }}">Errors</x-cockpit::nav.link>
                    <x-cockpit::nav.link :href="route('cockpit.reports.index')" active="{{ request()->routeIs('cockpit.reports.index') }}">Reports</x-cockpit::nav.link>
                    <a href="{{ COCKPIT_REPO }}" target="_blank">
                        <x-cockpit-icons icon="github" class="h-5 w-auto text-dark-700 dark:text-white"/>
                    </a>
                </div>
                <div class="-mr-2 flex items-center sm:hidden">
                    <x-cockpit::button x-on:click="open = !open" white sm aria-controls="mobile-menu"
                                       aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <x-cockpit-icons icon="menu" x-bind:class="{ 'block': !open, 'hidden': open }"/>
                        <x-cockpit-icons icon="x" x-bind:class="{ 'hidden': !open, 'block': open }"/>
                    </x-cockpit::button>
                </div>
            </div>
        </div>

        <div class="sm:hidden absolute bg-white dark:bg-dark-primary w-full" id="mobile-menu"
             x-show="open">
            <div class="pt-2 pb-3 space-y-1">
                <x-cockpit::nav.link mobile active>Errors</x-cockpit::nav.link>

                <x-cockpit::nav.link mobile>Reports</x-cockpit::nav.link>

                <x-cockpit::nav.link :href="COCKPIT_REPO" target="_blank" mobile>
                    GitHub
                </x-cockpit::nav.link>
            </div>
        </div>
    </div>
</nav>
