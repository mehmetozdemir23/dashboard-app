<template>
    <div class="bg-white flex flex-col shadow-md">
        <div class="h-72 overflow-y-scroll">
             <slot></slot>
        </div>
        <div class="w-full flex justify-end space-x-2 p-2 border-t border-slate-200 bg-white">
            <button type="button" @click.prevent="close"
                class="w-20 h-10 font-medium tracking-wide text-black text-sm capitalize transition-colors duration-200 transform bg-slate-200 hover:bg-slate-200 focus:outline-none">
                Cancel
            </button>
            <button type="button" @click.prevent="upload"
                class="w-20 h-10 font-medium tracking-wide text-white text-sm capitalize transition-colors duration-200 transform bg-indigo-600 hover:bg-indigo-500 focus:outline-none">
                Upload
            </button>
        </div>
    </div>
</template>
<script setup>

</script>
