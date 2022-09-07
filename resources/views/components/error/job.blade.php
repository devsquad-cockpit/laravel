@props(['occurrence'])

@php /** @var \Cockpit\Models\Occurrence $occurrence */ @endphp

<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="Job">

        <x-cockpit::error.section.content type="Job">
            {{ $occurrence->job['name'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Connection">
            {{ $occurrence->job['connection'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Queue">
            {{ $occurrence->job['queue'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Pushed At">
            {{ $occurrence->job['pushedAt'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Data" copyable code-type="json">
            @json($occurrence->job['data'] ?? [])
        </x-cockpit::error.section.content>

    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
