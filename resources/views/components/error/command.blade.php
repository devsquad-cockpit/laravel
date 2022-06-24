@props(['error'])

@php /** @var \Cockpit\Models\Error $error */ @endphp
<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="Command">
        <x-cockpit::error.section.content type="Command">
            {{ $error->command['command'] ?? null }}
        </x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Arguments" copyable code-type="json">
            @json($error->command['arguments'] ?? [])
        </x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
