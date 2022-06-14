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
                <x-cockpit::table.th>Error Message</x-cockpit::table.th>
                <x-cockpit::table.th>Occurrences</x-cockpit::table.th>
                <x-cockpit::table.th>Time</x-cockpit::table.th>
                <x-cockpit::table.th>Affected</x-cockpit::table.th>
                <x-cockpit::table.th>Status</x-cockpit::table.th>
                <x-cockpit::table.th></x-cockpit::table.th>
            </tr>
        </x-cockpit::table.thead>
    </x-cockpit::table>
</x-cockpit::app-layout>

