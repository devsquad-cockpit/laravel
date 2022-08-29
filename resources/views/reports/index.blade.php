<x-cockpit::app-layout>
    <div class="flex items-center justify-between mb-8" x-data="filter()">
        <h2 class="text-2xl text-primary font-bold">Reports</h2>

        <div class="flex items-center space-x-8">
            <x-cockpit::input.range-datepicker
                    name="from"
                    name-max="to"
                    labeless
                    :value="request()->get('from', $from)"
                    :value-max="request()->get('to')"
                    x-on:change="setTimeout(() => {
                    filter({
                    from: document.getElementById('from').value,
                    to: document.getElementById('to').value
                    });
                }, 300)"
            />
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

    <div x-data="chartArea(
            @js($totalErrors),
            @js($unresolvedErrors),
            @js($labels),
        )">
        <div x-ref="chartArea"></div>
    </div>

    <div class="flex-none mt-8">
        <p class="text-2xl text-white">Most Frequency Errors</p>
        <div class="mb-8">
            <x-cockpit::reports.frequency-error
                    :index="1"
                    occurrences="71,897"
                    error="Spatie\LaravelIgnition\Exceptions\ViewException: Mix manifest not found at: /home/aj/workstation/devsquad/cockpit/public/vendor/cockpit/mix-manifest.json"
                    :percentage="80"/>
            <x-cockpit::reports.frequency-error
                    :index="2"
                    occurrences="71,897"
                    error="Spatie\LaravelIgnition\Exceptions\ViewException: Mix manifest not found at: /home/aj/workstation/devsquad/cockpit/public/vendor/cockpit/mix-manifest.json"
                    :percentage="15"/>
            <x-cockpit::reports.frequency-error
                    :index="3"
                    occurrences="71,897"
                    error="Spatie\LaravelIgnition\Exceptions\ViewException: Mix manifest not found at: /home/aj/workstation/devsquad/cockpit/public/vendor/cockpit/mix-manifest.json"
                    :percentage="15"/>
        </div>
    </div>

</x-cockpit::app-layout>
