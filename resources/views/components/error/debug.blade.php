@props(['occurrence'])

@php /** @var \Cockpit\Models\Occurrence $occurrence */ @endphp

<x-cockpit::error.section {{ $attributes }} class="p-4">
    <x-cockpit::error.section.wrapper title="Debug">
        @foreach($occurrence->debug as $dump)
            <div class="col-span-4 text-sm">
                <a href="#" class="font-thin">
                    @foreach(array_filter(explode('/', str_replace(base_path(), '', $dump['file']))) as $path)
                        @if($loop->first)
                            {{ $path }} /
                        @else
                            <span class="font-bold">{{ $path . ':' . $dump['line_number'] }}</span>
                        @endif
                    @endforeach
                </a>

                <div class="mt-6" x-data="varDumper(@js($dump))">
                    <main>{!! $dump['html_dump'] !!}</main>
                </div>
            </div>
        @endforeach
    </x-cockpit::error.section.wrapper>
</x-cockpit::error.section>
