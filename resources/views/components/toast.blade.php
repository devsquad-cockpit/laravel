@php
    $hasToast = session()->has('toast');
    $type     = session('type');
    $message  = session('message');

    $icon = 'check-circle';
    if ($type == 'error') $icon = 'x-circle';
    if ($type == 'warning') $icon = 'exclamation-circle';

    $iconClasses = 'h-6 w-6';
    if ($type == 'success') $iconClasses .= ' text-green-400';
    if ($type == 'error') $iconClasses .= ' text-red-400';
    if ($type == 'warning') $iconClasses .= ' text-primary';
@endphp

@if ($hasToast)
    <div aria-live="assertive" class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
        <div class="w-full flex flex-col items-center space-y-4 sm:items-end"
             x-data="toast"
        >
            <div class="max-w-sm w-full bg-[#4E5356] shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
                 x-show="show"
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
            >
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <x-cockpit-icons :icon="$icon" :class="$iconClasses"/>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm text-gray-900 dark:text-white font-bold">
                                {{ ucfirst($type) }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-white">
                                {{ $message }}
                            </p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button type="button"
                                    class="bg-transparent rounded-md inline-flex text-gray-400 hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    @click="close"
                            >
                                <span class="sr-only">Close</span>
                                <x-cockpit-icons x class="h-5 w-5" :fill="false"/>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
