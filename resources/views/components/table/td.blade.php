<td {{ $attributes->class([
    'font-medium text-sm text-gray-500',
    'text-left' => !Str::contains($attributes->get('class'), ['text-right', 'text-center', 'text-justify'])
]) }} x-bind:class="getTdColumnClasses($el)">
    {{ $slot }}
</td>
