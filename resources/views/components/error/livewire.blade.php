@props(['occurrence'])

@php /** @var \Cockpit\Models\Occurrence $occurrence */ @endphp

<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="Livewire">
        <x-cockpit::error.section.content type="URL">
            {{ $occurrence->livewire['url'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Method">
            {{ $occurrence->livewire['method'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Class">
            {{ $occurrence->livewire['component_class'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Component Alias">
            {{ $occurrence->livewire['component_alias'] ?? '' }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Data" copyable code-type="json">
            @json($occurrence->livewire['data'] ?? [])
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Updates" copyable code-type="json">
            @json($occurrence->livewire['updates'] ?? [])
        </x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
