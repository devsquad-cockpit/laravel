@props(['data'])

<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="Routing">
        <x-cockpit::error.section.content type="Controller">{{ $data['controller'] }}</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Route Name">
            {{ $data['route']['name'] }}
        </x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Route Parameter">
            @if (is_array($data['route']['parameters']))
                @foreach ($data['route']['parameters'] as $parameter => $content)
                    <x-cockpit::error.section.sub :title="$parameter" :content="$content" code-type="json" />
                @endforeach
            @endif
        </x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="Middleware">
            @if (is_array($data['middlewares']))
                @foreach ($data['middlewares'] as $middleware)
                    <x-cockpit::error.section.sub :title="$middleware" :last="$loop->last" />
                @endforeach
            @endif
        </x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>
    <x-cockpit::error.section.wrapper title="View">
        <x-cockpit::error.section.content type="View Name">{{ $data['view']['name'] }}</x-cockpit::error.section.content>
        <x-cockpit::error.section.content type="View Data">
            @if (is_array($data['view']['data']))
                @foreach ($data['view']['data'] as $title => $viewData)
                    <x-cockpit::error.section.sub :title="$title" :content="$content" code-type="json" :last="$loop->last" />
                @endforeach
            @endif
        </x-cockpit::error.section.content>
    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
