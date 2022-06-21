<div class="w-full overflow-scroll">
    <div class="flex flex-col">
        <div
            class="flex items-stretch flex-grow overflow-x-auto fade-r overflow-y-hidden text-sm">

            <nav class="sticky left-0 flex flex-none z-20">
                <div class="select-none">
                    <template x-for="(code, line) in exceptionSelected.preview">
                        <p class="px-2 font-mono leading-loose select-none"
                           x-bind:class="line == exceptionSelected.line ? 'bg-red-500' : 'bg-[#393D3F]'"
                        >
                            <span class="dark:text-white text-gray-500" x-text="line"></span>
                        </p>
                    </template>
                </div>
            </nav>

            <div class="flex-grow pr-10 bg-[#27292B]">
                <template x-for="(code, line) in exceptionSelected.preview">
                    <div class="flex group items-center"
                         x-bind:class="line == exceptionSelected.line ? 'bg-red-600' : 'hover:bg-[#393D3F]'"
                    >
                        <x-cockpit::error.stacktrace.editor-link
                            x-bind:href="`{{ config('cockpit.editor') }}://open?file=${exceptionSelected.file}&line=${line}`"
                        />
                        <div class="px-2 font-mono leading-loose select-none text-sm">
                        <pre class="dark:text-white text-gray-500 bg-transparent"
                             x-text="code || '&nbsp;'"></pre>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
