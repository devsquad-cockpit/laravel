<x-cockpit::error.section {{ $attributes }}>
    <div class="grid grid-cols-3">
        <div class="p-4 w-full">
            <div class="flex items-center">
                <x-cockpit::input.toggle name="collapse-vendor-frames" label="Collapse vendor frames" value="1"
                                         current="1" x-on:change="toggleVendorFrames"/>
            </div>

            <div class="border border-gray-400 my-4 w-full"></div>

            <div class="w-full max-h-[400px] overflow-scroll custom-scrollbar">
                <x-cockpit::error.stacktrace.frame/>
            </div>
        </div>

        <div class="col-span-2" x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90">
            <x-cockpit::error.stacktrace.file/>
        </div>
    </div>
</x-cockpit::error.section>
