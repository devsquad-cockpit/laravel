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

    <x-cockpit::error.suggestion/>

    <div class="grid grid-cols-5 gap-4 mt-8">
        <x-cockpit::error.nav/>

        <x-cockpit::error.detail
            x-data="stackTrace({{ json_encode($exception['trace']) }})"
        >
            <div class="grid grid-cols-3">
                <div class="p-4 w-full">
                    <!-- Frames -->
                    <div class="flex items-center">
                        <span class="font-thin text-sm mr-2" id="collapse-vendor-frames">Collapse vendor frames</span>

                        <button type="button"
                                class="bg-gray-200 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                                role="switch"
                                aria-checked="false"
                                aria-labelledby="collapse-vendor-frames">

                            <span aria-hidden="true"
                                  class="translate-x-0 pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                        </button>
                    </div>

                    <div class="border border-gray-400 my-4 w-full"></div>

                    <div class="w-full">
                        <div class="flex justify-between items-center text-sm">
                            <div class="flex">
                                <div class="flex-shrink-0 mr-4">Illuminate\Validation\Validator::_call</div>
                            </div>
                            <div class="inline-flex">
                                <div class="flex-shrink-0">:1530</div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-sm whites">
                            <div class="flex">
                                home/forge/osi-dev.devsquadstage.com/releases/20220497112345/vendor/laravel/framework/src/illuminate/Validation/Validator.php
                            </div>
                            <div class="inline-flex">
                                <div class="flex-shrink-0">:1530</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-2">
                    <x-cockpit::error.error-line />
                </div>
            </div>
        </x-cockpit::error.detail>
    </div>
</x-cockpit::app-layout>
