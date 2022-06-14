<x-cockpit::app-layout>
    <div class="grid sm:grid-cols-3 gap-4 items-center">
        <x-cockpit::card.dashboard title="Errors per day" total="71897" percentage="12"/>
        <x-cockpit::card.dashboard title="Errors in the last hour" total="71897" percentage="12"/>
        <x-cockpit::card.dashboard title="Unresolved errors" total="71897" percentage="12"/>
    </div>

    <div class="flex items-center justify-between mt-14">
        <x-cockpit::input name="search" placeholder="Quick Search" labeless icon-left="magnifier"/>
        <div>Filter</div>
    </div>
</x-cockpit::app-layout>

