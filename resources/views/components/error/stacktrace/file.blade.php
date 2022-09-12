<div class="w-full custom-scrollbar">
    <div class="flex flex-col">
        <div
                class="flex items-stretch flex-grow overflow-x-auto overflow-y-hidden custom-scrollbar text-sm">

            <nav class="sticky left-0 flex flex-none z-20">
                <div class="select-none">
                    <template x-for="(code, line) in exceptionSelected.preview">
                        <p class="px-2 font-mono leading-loose select-none"
                           x-bind:class="line == exceptionSelected.line ? 'bg-red-500 dark:bg-red-500' : 'bg-gray-200 dark:bg-dark-even'">
                            <span class="text-gray-700 dark:text-white" x-text="line"></span>
                        </p>
                    </template>
                </div>
            </nav>

            <div class="flex-grow bg-white dark:bg-neutral-800 custom-scrollbar">
                <template x-for="(code, line) in exceptionSelected.preview">
                    <div class="flex group items-center"
                         x-bind:class="line == exceptionSelected.line ? 'bg-red-500 dark:bg-red-600' : 'hover:bg-gray-200 dark:hover:bg-dark-even'">
                        <x-cockpit::error.stacktrace.editor-link x-bind:href="opening(exceptionSelected.file, line)"/>
                        <div class="px-2 font-mono leading-loose select-none text-sm">
                        <pre class="bg-transparent"
                             x-text="code || '&nbsp;'"
                             x-bind:class="line == exceptionSelected.line ? 'text-white' : 'text-gray-700 dark:text-white'"></pre>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
