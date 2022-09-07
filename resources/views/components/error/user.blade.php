@props(['occurrence'])

@php /** @var \Cockpit\Models\Occurrence $occurrence */ @endphp

<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="User">
        <x-cockpit::error.section.content type="ID">
            {{ $occurrence->user['id'] ?? null }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Name">
            {{ $occurrence->user['name'] ?? null }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="E-mail">
            {{ $occurrence->user['email'] ?? null }}
        </x-cockpit::error.section.content>

        @isset($occurrence->user['guard'])
            <x-cockpit::error.section.content type="Auth guard">
                <x-cockpit::badge color="primary" xs bold>
                    {{ $occurrence->user['guard'] }}
                </x-cockpit::badge>
            </x-cockpit::error.section.content>
        @endisset

        @if (is_array($occurrence->user))
            <x-cockpit::error.section.content type="User Data" copyable code-type="json">
                @json(\Illuminate\Support\Arr::except($occurrence->user, 'guard'))
            </x-cockpit::error.section.content>
        @endif
    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
