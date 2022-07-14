@props(['occurrence'])

@php /** @var \Cockpit\Models\Occurrence $occurrence */ @endphp

<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="Context">
        <x-cockpit::error.section.content type="Data" copyable code-type="json">
            @json($occurrence->context)
        </x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
