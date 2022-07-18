@props(['occurrence'])

@php /** @var \Cockpit\Models\Occurrence $occurrence */ @endphp

<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="Routing">
        <x-cockpit::error.section.content type="Controller">{{ $occurrence->app['controller'] }}</x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Route Name">
            {{ $occurrence->app['route']['name'] }}
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Route Parameter">
            @if (is_array($occurrence->app['route']['parameters']))
                @foreach ($occurrence->app['route']['parameters'] as $parameter => $content)
                    <x-cockpit::error.section.sub :title="$parameter" :content="$content" code-type="json" />
                @endforeach
            @endif
        </x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="Middleware">
            @if (is_array($occurrence->app['middlewares']))
                @foreach ($occurrence->app['middlewares'] as $middleware)
                    <x-cockpit::error.section.sub :title="$middleware" :last="$loop->last" />
                @endforeach
            @endif
        </x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>

    <x-cockpit::error.section.wrapper title="View">
        <x-cockpit::error.section.content type="View Name">{{ $occurrence->app['view']['name'] }}</x-cockpit::error.section.content>

        <x-cockpit::error.section.content type="View Data">
            @if (is_array($occurrence->app['view']['data']))
                @foreach ($occurrence->app['view']['data'] as $title => $viewData)
                    <x-cockpit::error.section.sub :title="$title" :content="$viewData" code-type="json" :last="$loop->last" />
                @endforeach
            @endif
        </x-cockpit::error.section.content>

    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
