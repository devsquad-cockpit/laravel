@if ($outline)
    <svg xmlns="http://www.w3.org/2000/svg" {{ $attributes->class($classes) }} fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
    </svg>
@else
    <svg xmlns="http://www.w3.org/2000/svg" {{ $attributes->class($classes) }} viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd"
              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
              clip-rule="evenodd"/>
    </svg>
@endif
