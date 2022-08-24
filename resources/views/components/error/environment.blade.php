@props(['occurrence'])

@php /** @var \Cockpit\Models\Occurrence $occurrence */ @endphp

<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="Laravel">
        <x-cockpit::error.section.content type="Laravel version">{{ $occurrence->environment['laravel_version'] }}</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Laravel locale">{{ $occurrence->environment['laravel_locale'] }}</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Laravel config cached">{{ $occurrence->environment['laravel_config_cached'] ? 'True' : 'False' }}</x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>

    <x-cockpit::error.section.wrapper title="App">
        <x-cockpit::error.section.content type="App debug">{{ $occurrence->environment['app_debug'] }}</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="App env">{{ $occurrence->environment['app_env'] }}</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="App datetime">{{ $occurrence->environment['environment_date_time'] }}</x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>

     <x-cockpit::error.section.wrapper title="System">
        <x-cockpit::error.section.content type="PHP version">{{ $occurrence->environment['php_version'] }}</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="OS service">{{ $occurrence->environment['os_version'] }}</x-cockpit::error.section.content>
        @if($occurrence->environment['server_software'])
            <x-cockpit::error.section.content type="Server service">{{ $occurrence->environment['server_software'] }}</x-cockpit::error.section.content>
        @endif
        <x-cockpit::error.section.content type="Database version">{{ $occurrence->environment['database_version'] }}</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Browser version">{{ $occurrence->environment['browser_version'] }}</x-cockpit::error.section.content>
        @if($occurrence->environment['node_version'])
            <x-cockpit::error.section.content type="Node service">{{ $occurrence->environment['node_version'] }}</x-cockpit::error.section.content>
        @endif
        @if($occurrence->environment['npm_version'])
            <x-cockpit::error.section.content type="NPM service">{{ $occurrence->environment['npm_version'] }}</x-cockpit::error.section.content>
        @endif

    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
