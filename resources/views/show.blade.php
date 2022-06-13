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

    <x-cockpit::error.suggestion />

    <div class="grid grid-cols-5 gap-4 mt-8">
        <x-cockpit::error.nav/>

        <section class="col-span-4 w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-lg shadow px-2 py-2 space-y-1 w-full">
        </section>
    </div>
</x-cockpit::app-layout>
