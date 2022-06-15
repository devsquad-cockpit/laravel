<div class="w-full overflow-scroll bg-[#27292B]">
    <template x-for="(code, line) in exceptionSelected.preview">
        <div class="stack-trace flex flex-grow group cursor-pointer">
            <div class="bg-[#393D3F] flex items-center text-left px-2 sticky left-0"
                 x-text="line">
            </div>

            <div class="text-sm whitespace-normal py-1 px-3">
                <pre x-html="code"></pre>
            </div>
        </div>
    </template>
</div>
