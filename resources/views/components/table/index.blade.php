@props(['paginate' => null])

<div x-data="table()">
    <table {{ $attributes->class(['min-w-full']) }}>
        {{ $slot }}
    </table>
    
    {!! $paginate !!}
</div>
