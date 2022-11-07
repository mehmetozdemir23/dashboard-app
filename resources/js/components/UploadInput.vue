<template>
    <div class="flex flex-col items-center space-y-12">
        <h1 class="text-2xl mr-auto">
            Upload
        </h1>
        <div class="h-24 flex items-center justify-center">
            <div v-show="file" class="w-72 h-full flex justify-center items-center space-x-4 bg-slate-100 rounded-md">
                <span id="output-file" class="text-xl"></span>
                <button type="button" @click.prevent="file = null">
                    <img src="/images/minus-circle.svg" alt="" class="w-8">
                </button>
            </div>
            <label v-show="!file" for="file-upload"
                class="w-72 h-full flex justify-center items-center space-x-4 cursor-pointer border-dashed border-4 border-slate-500">
                <img src="/images/folder-plus.svg" alt="" class="w-12">
                <span class="text-2xl">Upload file</span>
                <input id="file-upload" type="file" class="hidden" @change="pickFile">
            </label>
        </div>

        <div class="ml-auto mt-20 flex space-x-4">
            <button type="button" @click.prevent="close"
                class="w-24 px-4 py-3 font-medium tracking-wide text-black capitalize transition-colors duration-200 transform bg-slate-300 rounded-md hover:bg-slate-200 focus:outline-none">
                Cancel
            </button>
            <button type="button" @click.prevent="upload"
                class="w-max px-4 py-3 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-600 rounded-md hover:bg-indigo-500 focus:outline-none">
                Upload
            </button>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';
const emit = defineEmits(['close','fileUploaded'])

const file = ref(null)
function pickFile(event) {
    const files = event.target.files || event.dataTransfer.files;
    file.value = files[0];

    const outputFile = document.querySelector("#output-file");
    outputFile.innerText = file.value.name
}
async function upload() {
    emit('fileUploaded',file)
}
function close() {
    emit('close')
}
</script>
