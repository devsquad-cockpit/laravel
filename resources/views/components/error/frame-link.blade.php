<template x-for="(trace, index) in stackTrace">
    <a class="flex justify-between items-center text-sm my-2 cursor-pointer hover:bg-dark hover:text-primary p-2 rounded"
       x-bind:class="selectedIndex === index ? 'border-r-2 border-primary text-primary' : '' "
       @click="selectException(index)"
    >
        <div class="flex break-all mr-4" x-text="trace.file"></div>
        <div class="inline-flex mr-1" x-text="':' + trace.line"></div>
    </a>
</template>
