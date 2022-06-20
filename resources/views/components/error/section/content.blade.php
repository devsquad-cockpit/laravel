@props(['type', 'copyable' => null, 'codeType' => null])

<div class="text-right font-bold tracking-wider">
    {{ $type }}
</div>
<div class="col-span-3">
    @if ($copyable && $codeType)
        @php $id = md5($type . uniqid()); @endphp
        <div x-data="copyable('{{ $id }}', '{{ $codeType }}')" class="relative rounded-md bg-dark-even p-4">
            <button type="button" x-on:click="copy()" class="absolute right-0 top-0 p-2 hover:text-primary"
                    x-bind:class="copied ? 'text-green-400' : 'text-white'" title="Copy to clipboard">
                <x-cockpit-icons icon="clipboard-copy" :fill="false"
                                 x-show="!copied"
                                 x-transition:enter="transition ease-in duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"/>
                <div class="flex items-center text-sm" x-show="copied"
                     x-transition:enter="transition ease-in duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100">
                    <x-cockpit-icons icon="check" class="mr-2" :fill="false"/>
                    Copied to clipboard
                </div>
            </button>
            <div id="{{ $id }}" class="hidden">{{ $slot }}</div>
            <pre x-ref="toCopy" class="whitespace-pre-wrap overflow-auto"></pre>
        </div>
    @else
        {{ $slot }}
    @endif
</div>
