<x-cockpit::app-layout>
    <div class="flex items-center justify-between mb-8" x-data="filter()">
        <h2 class="text-2xl text-primary font-bold">Reports</h2>

        <div class="flex items-center space-x-8">
            <x-cockpit::input.range-datepicker name="from" name-max="to" labeless
                                               :value="request()->get('from')" :value-max="request()->get('to')"
                                               x-on:change="setTimeout(() => {
                                                   filter({
                                                    from: document.getElementById('from').value,
                                                    to: document.getElementById('to').value
                                                   });
                                               }, 300)"/>
        </div>
    </div>

    <div class="flex-none mt-8">
        <p class="text-2xl text-white">Total Error Frequency</p>
        <div id="chart"></div>
    </div>

    <div class="flex-none mt-8">
        <p class="text-2xl text-white">Most Frequency Errors</p>
        <div>
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

    @push('scripts')
        <script>
            var options = {
                series: [{
                    name: 'Total Errors',
                    data: [11, 32, 45, 125, 34, 52, 41],
                    color: '#6FCF97'
                }, {
                    name: 'Unresolved Errors',
                    data: [31, 40, 28, 23, 42, 109, 100],
                    color: '#F2C94C'
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    foreColor: '#ffffff',
                    toolbar: {
                        show: false,
                        tools: {
                            download: false
                        }
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    type: 'date',
                    categories: ["19/09/2018", "20/09/2018","21/09/2018","22/09/2018","23/09/2018","24/09/2018","25/09/2018"]
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                },
            };

            (new ApexCharts(document.querySelector("#chart"), options)).render();
        </script>
    @endpush

</x-cockpit::app-layout>