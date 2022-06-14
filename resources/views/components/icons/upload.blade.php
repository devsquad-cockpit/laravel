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
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
    </svg>
@else
    <svg xmlns="http://www.w3.org/2000/svg"
         {{ $class }}
         viewBox="0 0 20 20"
         fill="currentColor"
    >
        <path
            d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/>
        <path d="M9 13h2v5a1 1 0 11-2 0v-5z"/>
    </svg>
@endif
