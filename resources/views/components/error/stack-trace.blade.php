<section {{ $attributes }} class="col-span-4 w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-lg shadow space-y-1 w-full">
    <div class="grid grid-cols-3">
        <div class="p-4 w-full">
            <!-- Frames -->
            <div class="flex items-center">
                <x-cockpit::input.toggle
                    name="collapse-vendor-frames"
                    label="Collapse vendor frames"
                    value="1"
                    current="1"
                    x-on:change="toggleVendorFrames"
                />
            </div>

            <div class="border border-gray-400 my-4 w-full"></div>

            <div class="w-full max-h-[400px] overflow-scroll">
                <x-cockpit::error.frame-link/>
            </div>
        </div>

        <div class="col-span-2"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
        >
            <x-cockpit::error.error-line/>
        </div>
    </div>
</section>
