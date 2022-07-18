@props(['occurrence'])

@php /** @var \Cockpit\Models\Occurrence $occurrence */ @endphp

<x-cockpit::error.section {{ $attributes }} class="p-4">
    @foreach ($occurrence->request as $title => $sectionData)
        <x-cockpit::error.section.wrapper :title="Str::spaceTitle($title)">
            @foreach ($sectionData as $type => $content)
                @if (is_array($content))
                    @if ($title == 'files')
                        <x-cockpit::error.section.content :type="$type" copyable="true" code-type="json">
                            @json($content)
                        </x-cockpit::error.section.content>
                    @else
                        <x-cockpit::error.section.content :type="$type" copyable="true" code-type="text" :wrap="false">
                            {{ implode(',', $content) }}
                        </x-cockpit::error.section.content>
                    @endif
                @else
                    <x-cockpit::error.section.content :type="$title == 'request' ? Str::title($type) : $type"
                                                      copyable="true" code-type="shell">
                        {{ $content }}
                    </x-cockpit::error.section.content>
                @endif
            @endforeach
        </x-cockpit::error.section.wrapper>
    @endforeach
</x-cockpit::error.section>
