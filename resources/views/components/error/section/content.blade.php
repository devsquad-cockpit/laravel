@props(['type', 'copyable' => null, 'codeType' => null])

<div class="text-right font-bold tracking-wider">
    {{ $type }}
</div>
<div class="col-span-3">
    @if ($copyable && $codeType)
        <x-cockpit::error.section.copyable :code-type="$codeType">
            {{ $slot }}
        </x-cockpit::error.section.copyable>
    @else
        {{ $slot }}
    @endif
</div>
