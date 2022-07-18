@props(['title', 'content' => null, 'code-type' => null, 'last' => false])

<blockquote class="border-l-2 border-gray-500 pl-4 flex flex-wrap">
    <div class="{{ $content || !$last ? 'py-2' : '' }} pr-6 text-gray-400">{{ $title }}</div>
    @if ($content && $codeType)
        <x-cockpit::error.section.copyable :code-type="$codeType">
            @json($content)
        </x-cockpit::error.section.copyable>
    @endif
</blockquote>
