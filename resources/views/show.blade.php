<x-cockpit::app-layout>
    <a href="{{ route('cockpit.index') }}"
       class="flex items-center text-gray-900 dark:text-white text-sm cursor-pointer">
        <x-cockpit::icons.arrow-left class="mr-3"/>
        Back
    </a>

    <x-cockpit::error.error-title>
        App\Jobs\ApiNotifications\NoLoginAfterPurchaseNotificationJob::_construct(): Argument #1 ($user) must be of type
        App\Models\User, null given, called in
    </x-cockpit::error.error-title>

    <span class="text-gray-900 dark:text-white text-sm">
        <div class="flex items-center">
            <x-cockpit::icons.link class="mr-3"/>
            http://devsquad.com/software-development-services/
        </div>
    </span>

    <div class="grid grid-cols-4 gap-3 items-center mt-6">
        <x-cockpit::card.error-status
            title="Latest Occurrence"
            value="12"
            description="mins ago"
        />

        <x-cockpit::card.error-status title="First Occurrence" value="12 Dec 2022"/>

        <x-cockpit::card.error-status title="# of occurrences" value="71839"/>
        <x-cockpit::card.error-status title="Affected Users" value="12"/>
    </div>

    <div class="mt-8">
        <x-cockpit::alert green outline>
            <div class="w-0 flex-1 flex items-center">
                <x-cockpit::icons.light-bulb/>
                <p class="ml-3 font-medium truncate">
                    <span class="inline">3 possible solutions found</span>
                </p>
            </div>
            <div class="order-3 mt-2 flex-shrink-0 w-full sm:order-2 sm:mt-0 sm:w-auto">
                <a href="#"
                   class="text-sm flex items-center justify-center px-3 text-sm ">
                    <x-cockpit::icons.arrow-down class="mr-3 h-3 w-3"/>
                    View
                </a>
            </div>
            <div class="order-2 flex-shrink-0 sm:order-3 sm:ml-2">
                <a href="#"
                   class="text-sm flex items-center justify-center text-sm ">
                    <x-cockpit::icons.x class="mr-3 h-4 w-4"/>
                    Dismiss
                </a>
            </div>
        </x-cockpit::alert>
    </div>

    <div class="grid grid-cols-4 gap-4 mt-8">
        <x-cockpit::error.nav/>

        <section class="col-span-3 w-full">

        </section>
    </div>
</x-cockpit::app-layout>
