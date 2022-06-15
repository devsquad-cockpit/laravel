@props(['href' => null])

@php
    $classes = [
        'relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-dark-even border-transparent leading-5',
        'cursor-pointer' => $href,
        'cursor-default disabled opacity-30' => !$href
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        {!! $slot !!}
    </a>
@else
    <span {{ $attributes->class($classes) }}>
        {!! $slot !!}
    </span>
@endif
