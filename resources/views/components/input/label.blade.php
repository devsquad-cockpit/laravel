@props(['for'])

<label for="{{ $for }}" {{ $attributes->class([
    'block text-sm font-medium leading-5 tracking-wider mb-1 transition-colors',
    'text-gray-700 dark:text-gray-400' => !$errors->has($for),
    'text-red-700 dark:text-red-400' => $errors->has($for)
]) }}>
    {{ $slot }}
</label>
