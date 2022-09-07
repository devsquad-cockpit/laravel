<template x-for="(trace, index) in filteredFrames">
    <a class="flex justify-between items-center text-sm my-2 cursor-pointer hover:bg-dark hover:text-primary p-2"
       x-bind:class="selectedIndex === index ? 'text-primary' : '' "
       @click="selectException(index)"
    >
        <div class="flex flex-col">
            <p class="flex">
                <span class="break-all mr-4" x-text="trace.class || trace.file"></span>
                <span class="mr-1" x-text="':' + trace.line"></span>
            </p>
            <p>
                <span class="font-bold" x-text="trace.function"></span>
            </p>
        </div>
    </a>
</template>
