<template x-for="(code, line) in exceptionSelected.line_preview">
    <div class="flex">
        <div class="bg-[#393D3F] w-14 flex items-center justify-center p-2"
             x-text="line"
        ></div>

        <div class="w-full bg-[#27292B] p-2 text-sm">
            <code x-text="code"></code>
        </div>
    </div>
</template>
