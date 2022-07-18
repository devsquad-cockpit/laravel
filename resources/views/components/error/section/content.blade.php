@props(['type', 'copyable' => null, 'codeType' => null, 'wrap' => true])

<div class="text-right font-bold tracking-wider">
    {{ $type }}
</div>
<div class="col-span-3">
    @if ($copyable && $codeType)
        <x-cockpit::error.section.copyable :code-type="$codeType" :wrap="$wrap">
            {{ $slot }}
        </x-cockpit::error.section.copyable>
    @else
        {{ $slot }}
    @endif
</div>
