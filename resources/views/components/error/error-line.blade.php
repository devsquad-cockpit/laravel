<div class="w-full overflow-scroll">
    <template x-for="(code, line) in exceptionSelected.preview">
        <div class="stack-trace flex flex-grow group">
            <div class="flex items-center text-left px-2 sticky left-0"
                 x-bind:class="line == exceptionSelected.line ? 'bg-red-500' : 'bg-[#393D3F]'"
                 x-text="line">
            </div>

            <div class="text-sm whitespace-normal py-1 px-3 flex items-center"
                 x-bind:class="line == exceptionSelected.line ? 'bg-red-600' : ''"
            >
                <x-cockpit::error.editor-link />
                <pre class="mr-3" x-html="code"></pre>
            </div>
        </div>
    </template>
</div>
