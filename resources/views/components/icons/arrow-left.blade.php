@if ($outline)
    <svg xmlns="http://www.w3.org/2000/svg" {{ $attributes->class($classes) }} fill="none" viewBox="0 0 24 24"
         stroke="currentColor"
         stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
@else
    <svg xmlns="http://www.w3.org/2000/svg" {{ $attributes->class($classes) }} viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd"
              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
              clip-rule="evenodd"/>
    </svg>
@endif