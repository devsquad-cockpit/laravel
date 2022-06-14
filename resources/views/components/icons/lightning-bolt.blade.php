@props(['outline' => null])

@php($class = $attributes->class(['h-6 w-6' => !Str::contains($attributes->get('class'), ['h-', 'w-'])]))

@if($outline)
    <svg xmlns="http://www.w3.org/2000/svg"
         {{ $class }}
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor"
         stroke-width="2"
    >
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
    </svg>
@else
    <svg xmlns="http://www.w3.org/2000/svg"
         {{ $class }}
         viewBox="0 0 20 20"
         fill="currentColor"
    >
        <path fill-rule="evenodd"
              d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
              clip-rule="evenodd"/>
    </svg>
@endif
