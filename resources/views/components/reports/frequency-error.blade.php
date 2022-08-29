@props([
    'index',
    'error',
    'occurrences',
])

@php
    $percentage = round(error_percentage($error->occurrences_count, $occurrences), 2)
@endphp

<div class="flex p-8 mt-4">
    <div class="flex items-center mr-8">
        <div class="relative w-12 h-12 border-4 border-primary rounded-full flex justify-center items-center text-center text-primary font-semibold text-2xl p-5 shadow-xl">
            {{ $index }}
        </div>
    </div>
    <div class="w-full">
        <p class="text-white mb-4">
            {{ $error->description }}
        </p>
        <div class="flex justify-between mb-6">
            <h2 class="text-2xl font-semibold text-primary">{{ $error->occurrences_count }}</h2>
            <p class="text-white">
                <x-cockpit::badge color="primary" xs bold>
                    {{ $percentage }}%
                </x-cockpit::badge>
            </p>
        </div>
        <div class="w-full mt-4 h-7 rounded-md dark:bg-gray-500">
            <div class="bg-primary rounded-md h-7" style="width: {{ $percentage }}%"></div>
        </div>
    </div>
</div>
