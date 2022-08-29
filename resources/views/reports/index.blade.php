<x-cockpit::app-layout>
    <div class="flex items-center justify-between mb-8" x-data="filter()">
        <h2 class="text-2xl text-primary font-bold">Reports</h2>

        <div class="flex items-center space-x-8">
            <x-cockpit::input.range-datepicker name="from" name-max="to" labeless
                :value="request()->get('from', $from)" :value-max="request()->get('to')"
                x-on:change="setTimeout(() => {
                    filter({
                    from: document.getElementById('from').value,
                    to: document.getElementById('to').value
                    });
                }, 300)"/>
        </div>
    </div>

    <div class="flex justify-between mt-8">
        <div>
            <p class="text-2xl text-white">Total Error Frequency</p>
        </div>
        <div class="inline-flex text-white">
            <div class="px-2">
                <div class="inline-flex px-2 py-2 bg-green-500 mr-1"></div>
                Total Errors
            </div>
            <div class="px-2">
                <div class="inline-flex px-2 py-2 bg-primary mr-1"></div>
                Unresolved Errors
            </div>
        </div>
    </div>

    <div
        x-data="
            chartArea(
                @js($totalErros),
                @js($unresolvedErrors),
                @js($labels),
            )
        "

    >
        <div x-ref="chartArea"></div>
    </div>

    <div class="flex-none mt-8">
        <p class="text-2xl text-white">Most Frequency Errors</p>
        <div class="mb-8">
            <div class="flex p-8 mt-4">
                <div class="flex items-center mr-8">
                    <div class="relative w-12 h-12 border-4 border-primary rounded-full flex justify-center items-center text-center text-primary font-semibold text-2xl p-5 shadow-xl">
                        1
                    </div>
                </div>
                <div>
                    <p class="text-white mb-4">
                        Spatie\LaravelIgnition\Exceptions\ViewException: Mix manifest not found at: /home/aj/workstation/devsquad/cockpit/public/vendor/cockpit/mix-manifest.json
                    </p>
                    <div class="flex justify-between mb-6">
                        <h2 class="text-2xl font-semibold text-primary">71,897</h2>
                        <p class="text-white">
                            <x-cockpit::badge color="primary" xs bold>
                                12%
                            </x-cockpit::badge>
                        </p>
                    </div>
                    <div class="w-full mt-4 h-7 rounded-md dark:bg-gray-500">
                        <div class="bg-primary rounded-md h-7" style="width: 45%"></div>
                    </div>
                </div>
            </div>
            <div class="flex p-8 mt-4">
                <div class="flex items-center mr-8">
                    <div class="relative w-12 h-12 border-4 border-primary rounded-full flex justify-center items-center text-center text-primary font-semibold text-2xl p-5 shadow-xl">
                        2
                    </div>
                </div>
                <div>
                    <p class="text-white mb-4">
                        Spatie\LaravelIgnition\Exceptions\ViewException: Mix manifest not found at: /home/aj/workstation/devsquad/cockpit/public/vendor/cockpit/mix-manifest.json
                    </p>
                    <div class="flex justify-between mb-6">
                        <h2 class="text-2xl font-semibold text-primary">71,897</h2>
                        <p class="text-white">
                            <x-cockpit::badge color="primary" xs bold>
                                12%
                            </x-cockpit::badge>
                        </p>
                    </div>
                    <div class="w-full mt-4 h-7 rounded-md dark:bg-gray-500">
                        <div class="bg-primary rounded-md h-7" style="width: 45%"></div>
                    </div>
                </div>
            </div>
            <div class="flex p-8 mt-4">
                <div class="flex items-center mr-8">
                    <div class="relative w-12 h-12 border-4 border-primary rounded-full flex justify-center items-center text-center text-primary font-semibold text-2xl p-5 shadow-xl">
                        3
                    </div>
                </div>
                <div>
                    <p class="text-white mb-4">
                        Spatie\LaravelIgnition\Exceptions\ViewException: Mix manifest not found at: /home/aj/workstation/devsquad/cockpit/public/vendor/cockpit/mix-manifest.json
                    </p>
                    <div class="flex justify-between mb-6">
                        <h2 class="text-2xl font-semibold text-primary">71,897</h2>
                        <p class="text-white">
                            <x-cockpit::badge color="primary" xs bold>
                                12%
                            </x-cockpit::badge>
                        </p>
                    </div>
                    <div class="w-full mt-4 h-7 rounded-md dark:bg-gray-500">
                        <div class="bg-primary rounded-md h-7" style="width: 45%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-cockpit::app-layout>
