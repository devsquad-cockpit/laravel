@props(['occurrence'])

@php /** @var \Cockpit\Models\Occurrence $occurrence */ @endphp

@php
    dd($occurrence);
@endphp

<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="Laravel">
        <x-cockpit::error.section.content type="Version">{{ $occurrence->environment['laravel_version'] }}</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Locale">{{ $occurrence->environment['laravel_locale'] }}</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Cached">{{ $occurrence->environment['laravel_config_cached'] ? 'True' : 'False' }}</x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>

    <x-cockpit::error.section.wrapper title="App">
        <x-cockpit::error.section.content type="Environment">{{ $occurrence->environment['app_env'] }}</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Debug">{{ $occurrence->environment['app_debug'] }}</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Datetime">{{ $occurrence->environment['environment_date_time'] }}</x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>

     <x-cockpit::error.section.wrapper title="System">
        <x-cockpit::error.section.content type="PHP">{{ $occurrence->environment['php_version'] }}</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="OS">{{ $occurrence->environment['os_version'] }}</x-cockpit::error.section.content>
        @if($occurrence->environment['server_software'])
            <x-cockpit::error.section.content type="Server">{{ $occurrence->environment['server_software'] }}</x-cockpit::error.section.content>
        @endif
        <x-cockpit::error.section.content type="Database">{{ $occurrence->environment['database_version'] }}</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Browser">{{ $occurrence->environment['browser_version'] }}</x-cockpit::error.section.content>
        @if($occurrence->environment['node_version'])
            <x-cockpit::error.section.content type="Node">{{ $occurrence->environment['node_version'] }}</x-cockpit::error.section.content>
        @endif
        @if($occurrence->environment['npm_version'])
            <x-cockpit::error.section.content type="NPM">{{ $occurrence->environment['npm_version'] }}</x-cockpit::error.section.content>
        @endif

    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
