<nav class="bg-white dark:bg-dark-primary shadow-sm dark:border-b dark:border-gray-900" x-data="{ open: false, settings: false }">
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
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-3">
                    <x-cockpit::nav.link :href="route('cockpit.index')" active="{{ request()->routeIs('cockpit.index') || request()->routeIs('cockpit.show') }}">Errors</x-cockpit::nav.link>
                    <x-cockpit::nav.link :href="route('cockpit.reports.index')" active="{{ request()->routeIs('cockpit.reports.index') }}">Reports</x-cockpit::nav.link>
                    <a href="{{ COCKPIT_REPO }}" target="_blank">
                        <x-cockpit-icons icon="github" class="h-5 w-auto text-dark-700 dark:text-white"/>
                    </a>
                    <div class="relative inline-block text-left">
                        <div>
                            <x-cockpit-icons @click="settings = !settings" icon="cog" class="h-6 h-6 cursor-pointer" outline />
                        </div>
                        <div
                            x-show="settings"
                            @click.outside="settings = false"
                            x-transition:enter="transition ease-out duration-75"
                            x-transition:enter-start="transform opacity-0 scale-90"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-10 mt-8 w-60 origin-top-right rounded-md bg-white dark:bg-dark-primary shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                            <div class="py-1" role="none">
                                <div class="mx-2 my-4">
                                    <div class="flex items-center space-x-2">
                                        <x-cockpit::input.alpine.toggle trigger="darkMode" :label="__('Dark Mode')" />
                                    </div>
                                </div>
                                <div class="mx-2 my-4">
                                    <div class="flex items-center space-x-2">
                                        <x-cockpit::input.alpine.toggle trigger="errorLayoutNavBar">
                                            {{ __('Small Error Detail') }}
                                            <x-cockpit-icons icon="question" class="ml-1 h-4 w-4 text-gray-700 dark:text-primary" x-tooltip="Switches error detail tabs from sidebar to navbar" />
                                        </x-cockpit::input.alpine.toggle>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

        <div class="sm:hidden absolute bg-white dark:bg-dark-primary w-full" id="mobile-menu" x-show="open">
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
