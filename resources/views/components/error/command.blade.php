@props(['occurrence'])

@php /** @var \Cockpit\Models\Occurrence $occurrence */ @endphp

<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="Command">
        <x-cockpit::error.section.content type="Command">
            {{ $occurrence->command['command'] ?? null }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Arguments" copyable code-type="json">
            @json($occurrence->command['arguments'] ?? [])
        </x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
