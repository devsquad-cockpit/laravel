@props(['error'])

@php /** @var \Cockpit\Models\Error $error */ @endphp
<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="Job">

        <x-cockpit::error.section.content type="Job">
            {{ $error->job['name'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Connection">
            {{ $error->job['connection'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Queue">
            {{ $error->job['queue'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Pushed At">
            {{ $error->job['pushedAt'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Data" copyable code-type="json">
            @json($error->job['data'] ?? [])
        </x-cockpit::error.section.content>

    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
