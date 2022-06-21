@props(['error'])

@php /** @var \Cockpit\Models\Error $error */ @endphp
<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="User">
        <x-cockpit::error.section.content type="ID">
            {{ $error->user['id'] ?? null }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Name">
            {{ $error->user['name'] ?? null }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="E-mail">
            {{ $error->user['email'] ?? null }}
        </x-cockpit::error.section.content>

        @isset($error->user['guard'])
            <x-cockpit::error.section.content type="Auth guard">
                <x-cockpit::badge color="primary" xs bold>
                    {{ $error->user['guard'] }}
                </x-cockpit::badge>
            </x-cockpit::error.section.content>
        @endisset

        <x-cockpit::error.section.content type="User Data" copyable code-type="json">
            @if (is_array($error->user))
                @json(\Illuminate\Support\Arr::except($error->user, 'guard'))
            @endif
        </x-cockpit::error.section.content>

    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
