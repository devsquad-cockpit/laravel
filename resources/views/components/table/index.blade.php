@props(['paginate' => null])

<div x-data="table()" class="sm:overflow-x-auto md:overflow-hidden flex flex-wrap flex-col">
    <table {{ $attributes->class(['min-w-full']) }}>
        {{ $slot }}
    </table>

    {!! $paginate !!}
</div>
