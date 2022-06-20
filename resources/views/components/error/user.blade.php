@props(['error'])

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

        <x-cockpit::error.section.content type="User Data" copyable code-type="json">
            {{ json_encode($error->user ?? []) }}
        </x-cockpit::error.section.content>

    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
