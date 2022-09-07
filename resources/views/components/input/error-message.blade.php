@props(['for'])

@error($for)
<p class="mt-2 text-sm text-dark-primary dark:text-red-400">
    {{ $message }}
</p>
@enderror
