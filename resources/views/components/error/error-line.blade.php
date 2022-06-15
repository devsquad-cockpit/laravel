<template x-for="(code, line) in exceptionSelected.preview">
    <div class="stack-trace flex group hover:bg-white hover:opacity-60 cursor-pointer">
        <div class="bg-[#393D3F] w-14 flex items-center justify-center px-2 py-1"
             x-text="line">
        </div>

        <div class="w-full bg-[#27292B] flex items-center px-2 text-sm whitespace-normal">
            <pre x-html="code"></pre>
        </div>
    </div>
</template>
