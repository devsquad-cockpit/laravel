<x-cockpit::app-layout>
    <div class="grid sm:grid-cols-3 gap-4 items-center">
        <x-cockpit::card.dashboard title="Errors per day" total="71897" percentage="12"/>
        <x-cockpit::card.dashboard title="Errors in the last hour" total="71897" percentage="12"/>
        <x-cockpit::card.dashboard title="Unresolved errors" total="71897" percentage="12"/>
    </div>

    <div class="flex items-center justify-between mt-14">
        <x-cockpit::input name="search" placeholder="Quick Search" labeless icon-left="search"
                          wrapper-class="w-full sm:w-64"/>

        <div class="flex items-center space-x-8">
            <x-cockpit::input.toggle name="unresolved" label="Show Unresolved Only"/>
            <x-cockpit::input.range-datepicker name="from" name-max="to" labeless/>
        </div>
    </div>

    <x-cockpit::table class="mt-8">
        <x-cockpit::table.thead>
            <tr>
                <x-cockpit::table.th sort-by="error_message">Error Message</x-cockpit::table.th>
                <x-cockpit::table.th sort-by="occurrences">Occurrences</x-cockpit::table.th>
                <x-cockpit::table.th sort-by="created_at">Time</x-cockpit::table.th>
                <x-cockpit::table.th sort-by="affected">Affected</x-cockpit::table.th>
                <x-cockpit::table.th sort-bu="status">Status</x-cockpit::table.th>
                <x-cockpit::table.th></x-cockpit::table.th>
            </tr>
        </x-cockpit::table.thead>
        <x-cockpit::table.tbody>
            @for ($i = 0; $i < 10; $i++)
                <tr>
                    <x-cockpit::table.td>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur assumenda aut, blanditiis commodi dolorem doloremque, ea eum expedita fugiat illum laborum non officia, sequi totam velit veritatis vitae. Necessitatibus, porro.
                        <a href="#" class="flex items-center space-x-2 mt-3">
                            <x-cockpit-icons icon="link" class="h-4 w-4 text-primary"/>
                            <span>http://test.com</span>
                        </a>
                    </x-cockpit::table.td>
                    <x-cockpit::table.td class="w-44">234</x-cockpit::table.td>
                    <x-cockpit::table.td class="w-28">{{ now()->format('H:i e') }}</x-cockpit::table.td>
                    <x-cockpit::table.td class="w-36">1234</x-cockpit::table.td>
                    <x-cockpit::table.td>
                        @if (rand(0,1))
                            <x-cockpit::badge color="green" xs bold>Resolved</x-cockpit::badge>
                        @else
                            <x-cockpit::badge color="red" xs bold>Unresolved</x-cockpit::badge>
                        @endif
                    </x-cockpit::table.td>
                    <x-cockpit::table.td>
                        <a href="#">
                            <x-cockpit-icons icon="arrow-right"/>
                        </a>
                    </x-cockpit::table.td>
                </tr>
            @endfor
        </x-cockpit::table.tbody>
    </x-cockpit::table>
</x-cockpit::app-layout>

