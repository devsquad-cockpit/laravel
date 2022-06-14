@props(['outline' => null])

@php
    $classes = ['h-6 w-6' => !Str::contains($attributes->get('class'), ['h-', 'w-'])];
@endphp

@if ($outline)
    <svg xmlns="http://www.w3.org/2000/svg" {{ $attributes->class($classes) }} fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
    </svg>
@else
    <svg xmlns="http://www.w3.org/2000/svg" {{ $attributes->class($classes) }} viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd"/>
    </svg>
@endif
