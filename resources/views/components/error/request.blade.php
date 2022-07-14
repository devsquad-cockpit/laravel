@props(['occurrence'])

@php /** @var \Cockpit\Models\Occurrence $occurrence */ @endphp

<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="Request">
        <x-cockpit::error.section.content type="URL"></x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Method"></x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Curl"></x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>

    <x-cockpit::error.section.wrapper title="Headers">
        <x-cockpit::error.section.content type="Cookie"></x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Accept-Language"></x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Accept-Encoding"></x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Something"></x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>

    <x-cockpit::error.section.wrapper title="Query String">
        <x-cockpit::error.section.content type="Something"></x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>

    <x-cockpit::error.section.wrapper title="Body">
        <x-cockpit::error.section.content type="Something"></x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>

    <x-cockpit::error.section.wrapper title="Files">
        <x-cockpit::error.section.content type="Something"></x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>

    <x-cockpit::error.section.wrapper title="Session">
        <x-cockpit::error.section.content type="Something"></x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>

    <x-cockpit::error.section.wrapper title="Cookies">
        <x-cockpit::error.section.content type="Something"></x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
