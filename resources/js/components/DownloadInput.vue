<template>
    <div class="flex flex-col space-y-12 items-center">

        <h1 class="text-2xl mr-auto">
            Download
        </h1>
        <div class="flex flex-col">
            <div class="flex items-center">
                <input type="text" v-model="file.name"
                    class="flex-1 max-w-sm py-2 pl-4 text-3xl bg-slate-200 border-b-2 border-slate-600 focus:outline-none">
                <span class="px-2 text-3xl">.xlsx</span>
            </div>
            <div class="h-12 flex items-center">
                <div v-if="!file.name" class="flex space-x-2">
                    <img src="/images/exclamation-triangle.svg" alt="" class="w-6">
                    <p class="text-red-500">{{ file.error }}</p>
                </div>
            </div>


        </div>
        <div class="ml-auto mt-20 flex space-x-4">

            <button type="button" @click.prevent="close"
                class="w-24 px-4 py-3 font-medium tracking-wide text-black capitalize transition-colors duration-200 transform bg-slate-300 rounded-md hover:bg-slate-200 focus:outline-none">
                Cancel
            </button>
            <button type="button" @click.prevent="download"
                class="w-max px-4 py-3 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-600 rounded-md hover:bg-indigo-500 focus:outline-none">
                Download
            </button>
        </div>
    </div>
</template>
<script setup>

import { reactive } from 'vue';
const emit = defineEmits(['close','fileDownloaded'])
const file = reactive({
    name: generateFileName(),
    error: 'Missing file name'
})

async function download() {
    emit('fileDownloaded',file)
    // const fileNameRegex = /^\w+[\w,\s-()[]]*$/
    // let fileName = file.name
    // if (fileName.match(fileNameRegex)) {
    //     const response = await grumeStore.download(file.name)
    //     if (response) {
    //         let link = document.createElement('a')
    //         link.href = window.URL.createObjectURL(response.data)
    //         fileName = fileName || generateFileName()
    //         link.download = `${fileName}.xlsx`
    //         link.click()
    //         link.remove()
    //         close()
    //     }
    // }
}

function close() {
    emit('close')
}
function generateFileName() {
    const date = new Date(Date.now())
    const day = date.getDate();
    const month = date.getMonth() + 1
    const fullYear = date.getFullYear()
    const formattedDate = day + '_' + (month < 10 ? 0 : '') + month + '_' + fullYear
    return `grumes_${formattedDate}`
}
</script>
