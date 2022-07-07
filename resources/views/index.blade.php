<x-cockpit::app-layout>
    <div class="grid sm:grid-cols-3 gap-4 items-center">
        <x-cockpit::card.dashboard title="Occurrences per day - Average"
                                   :total="$errorsPerDay"/>

        <x-cockpit::card.dashboard title="Occurrences in the last hour"
                                   :total="$errorsLastHour"
                                   :percentage="error_percentage($errorsLastHour, $totalOccurrences)"/>

        <x-cockpit::card.dashboard title="Unresolved errors"
                                   :total="$unresolvedErrors"
                                   :percentage="error_percentage($unresolvedErrors, $totalErrors)"/>
    </div>

    <div class="flex items-center justify-between mt-14" x-data="filter()">
        <x-cockpit::input name="search" placeholder="Quick Search" labeless icon-left="search"
                          :value="request()->get('search')" x-on:focusout="filter($el)" wrapper-class="w-full sm:w-64"/>

        <div class="flex items-center space-x-8">
            <x-cockpit::input.toggle name="unresolved" label="Show Unresolved Only"
                                     :current="request()->get('unresolved')" x-on:change="filter($el, +$el.checked)"/>
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

    <x-cockpit::table class="mt-8" :paginate="$cockpitErrors->withQueryString()->links()">
        <x-cockpit::table.thead>
            <tr>
                <x-cockpit::table.th sort-by="error_message">Error Message</x-cockpit::table.th>
                <x-cockpit::table.th sort-by="occurrences">Occurrences</x-cockpit::table.th>
                <x-cockpit::table.th sort-by="last_occurrence_at" default>Last Occurrence</x-cockpit::table.th>
                <x-cockpit::table.th sort-by="affected">Affected</x-cockpit::table.th>
                <x-cockpit::table.th sort-by="resolved_at">Status</x-cockpit::table.th>
                <x-cockpit::table.th></x-cockpit::table.th>
            </tr>
        </x-cockpit::table.thead>
        <x-cockpit::table.tbody>
            @php /** @var \Cockpit\Models\Error $cockpitError */ @endphp
            @foreach ($cockpitErrors as $cockpitError)
                <tr>
                    <x-cockpit::table.td class="break-all space-y-2">
                        <p class="text-lg font-bold">{{ $cockpitError->exception }}</p>
                        <p>{{ Str::limit($cockpitError->message, 400) }}</p>
                        @if ($cockpitError->url)
                            <div class="mt-2">
                                <a href="{{ $cockpitError->url }}"
                                   class="hover:underline">
                                    <x-cockpit-icons icon="link" class="h-4 w-4 text-primary mr-1 inline"/>
                                    {{ $cockpitError->url }}
                                </a>
                            </div>
                        @endif
                    </x-cockpit::table.td>
                    <x-cockpit::table.td>{{ $cockpitError->occurrences }}</x-cockpit::table.td>
                    <x-cockpit::table.td>
                        {{ $cockpitError->last_occurrence_at->diffForHumans() }}
                    </x-cockpit::table.td>
                    <x-cockpit::table.td>{{ $cockpitError->affected_users }}</x-cockpit::table.td>
                    <x-cockpit::table.td>
                        @if ($cockpitError->was_resolved)
                            <x-cockpit::badge color="green" xs bold>
                                Resolved
                            </x-cockpit::badge>
                        @else
                            <x-cockpit::badge color="red" xs bold>Unresolved</x-cockpit::badge>
                        @endif
                    </x-cockpit::table.td>
                    <x-cockpit::table.td>
                        <a href="{{ route('cockpit.show', $cockpitError) }}">
                            <x-cockpit-icons icon="arrow-right"/>
                        </a>
                    </x-cockpit::table.td>
                </tr>
            @endforeach
        </x-cockpit::table.tbody>
    </x-cockpit::table>
</x-cockpit::app-layout>

