@props(['outline' => null])

@if($outline)
    <svg xmlns="http://www.w3.org/2000/svg"
         {{ $attributes->class(['h-6 w-6' => !Str::contains($attributes->get('class'), ['h-', 'w-'])]) }}
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor"
         stroke-width="2"
    >
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
    </svg>
@else
    <svg xmlns="http://www.w3.org/2000/svg"
         {{ $attributes->class(['h-6 w-6' => !Str::contains($attributes->get('class'), ['h-', 'w-'])]) }}
         viewBox="0 0 20 20"
         fill="currentColor"
    >
        <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
    </svg>
@endif
