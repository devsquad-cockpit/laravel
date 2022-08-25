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

    <div id="chart"></div>

    @if($errors->total() > 0)
        <div class="flex-none mt-8" x-data="table()">
            <p class="text-2xl text-white">Most Frequency Errors</p>
            <div class="mb-8">
                @foreach($errors as $error)
                    @php ($percentage = round(($error->occurrences_count * 100) / $ocurrences, 2))
                    <div class="flex p-8 mt-4">
                        <div class="flex items-center mr-8">
                            <div class="relative w-6 h-6 border-4 border-primary rounded-full flex justify-center items-center text-center text-primary font-semibold text-lg p-5 shadow-xl">
                                {{ $loop->iteration }}
                            </div>
                        </div>
                        <div class="w-full">
                            <p class="text-white mb-4">
                                {{ sprintf('%s: %s', $error->exception, $error->message) }}
                            </p>
                            <div class="flex justify-between mb-6">
                                <h2 class="text-2xl font-semibold text-primary">{{ $error->occurrences_count }}</h2>
                                <p class="text-white">
                                    <x-cockpit::badge color="primary" xs bold>
                                        {{ $percentage }}%
                                    </x-cockpit::badge>
                                </p>
                            </div>
                            <div class="w-full mt-4 h-7 rounded-md dark:bg-gray-500">
                                <div class="bg-primary rounded-md h-7" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
                {{ $errors->onEachSide(0)->links() }}
            </div>
        </div>
    @endif

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