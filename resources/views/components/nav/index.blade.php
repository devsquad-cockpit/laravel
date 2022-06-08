<nav class="bg-white dark:bg-gray-900 shadow-sm dark:border-b dark:border-gray-900" x-data="{ open: false }">
    <div class="relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <x-cockpit::app-logo class="h-8 w-auto"/>
                    </div>
                    <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                        <x-cockpit::nav.link active>Errors</x-cockpit::nav.link>
                        <x-cockpit::nav.link>Reports</x-cockpit::nav.link>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-3">
                    <x-cockpit::badge red bold>{{ config('app.env') }}</x-cockpit::badge>
                    <x-cockpit::nav.link :href="COCKPIT_REPO" target="_blank" no-border>
                        <x-cockpit::icons.github class="h-7 w-auto text-white"/>
                    </x-cockpit::nav.link>
                </div>
                <div class="-mr-2 flex items-center sm:hidden">
                    <x-cockpit::button x-on:click="open = !open" white sm aria-controls="mobile-menu"
                                       aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <x-cockpit::icons.menu x-bind:class="{ 'block': !open, 'hidden': open }"/>
                        <x-cockpit::icons.x x-bind:class="{ 'hidden': !open, 'block': open }"/>
                    </x-cockpit::button>
                </div>
            </div>
        </div>

        <div class="sm:hidden absolute bg-white dark:bg-gray-900 w-full" id="mobile-menu"
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
