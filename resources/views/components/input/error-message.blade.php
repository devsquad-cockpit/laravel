@props(['for'])

@error($for)
<p class="mt-2 text-sm text-red-700 dark:text-red-400">
    {{ $message }}
</p>
@enderror
