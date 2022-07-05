@props(['error'])

@php /** @var \Cockpit\Models\Error $error */ @endphp
<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="Livewire">
        <x-cockpit::error.section.content type="URL">
            {{ $error->livewire['url'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Method">
            {{ $error->livewire['method'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Class">
            {{ $error->livewire['component_class'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Component Alias">
            {{ $error->livewire['component_alias'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Data" copyable code-type="json">
            @json($error->livewire['data'] ?? [])
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Updates" copyable code-type="json">
            @json($error->livewire['updates'] ?? [])
        </x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
