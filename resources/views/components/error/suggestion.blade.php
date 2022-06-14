<div class="mt-8">
    <x-cockpit::alert green outline>
        <div class="w-0 flex-1 flex items-center">
            <x-cockpit-icons icon="light-bulb"/>
            <p class="ml-3 font-medium truncate">
                <span class="inline">3 possible solutions found</span>
            </p>
        </div>
        <div class="order-3 mt-2 flex-shrink-0 w-full sm:order-2 sm:mt-0 sm:w-auto">
            <a href="#"
               class="text-sm flex items-center justify-center px-3 text-sm ">
                <x-cockpit-icons icon="arrow-down" class="mr-3 h-3 w-3"/>
                View
            </a>
        </div>
        <div class="order-2 flex-shrink-0 sm:order-3 sm:ml-2">
            <a href="#"
               class="text-sm flex items-center justify-center text-sm ">
                <x-cockpit-icons icon="x" class="mr-3 h-4 w-4"/>
                Dismiss
            </a>
        </div>
    </x-cockpit::alert>
</div>
