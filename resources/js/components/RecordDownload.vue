<template>
    <div class="bg-white flex flex-col shadow-md">
        <div class="bg-slate-300">
            <div class="w-full h-8 flex items-center bg-white pl-4">
                <h1>Download {{ model.name }}</h1>
            </div>
            <div class="flex flex-col h-64 overflow-y-scroll ">
                <div class="flex sticky z-50 top-0 p-4 bg-sky-500">
                    <input type="text" v-model="record"
                        class="h-auto w-full pl-2 outline-none focus:border-2 border-indigo-600">
                    <button @click.prevent="recordsToDownload.add(record)" type="button"
                        class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-indigo-600">
                        <img src="/images/plus-white.svg" alt="" class="w-6">
                    </button>
                </div>
                <ul class="grid grid-cols-2 p-4 gap-2 flex-1">
                    <li v-for="(record, index) in recordsToDownload" :key="index"
                        class="relative w-full h-10 flex justify-center items-center text-white bg-indigo-600">
                        <span class="">
                            {{ record }}
                        </span>
                        <button @click.prevent="recordsToDownload.delete(record)" type="button"
                            class="absolute right-2">
                            <img src="/images/x-mark-white.svg" alt="" class="w-6">
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="w-full flex justify-end space-x-2 p-2 border-t border-slate-200 bg-white">
            <button type="button" @click.prevent="cancel"
                class="w-24 h-10 font-medium tracking-wide text-black text-sm capitalize transition-colors duration-200 transform bg-slate-200 hover:bg-slate-200 focus:outline-none">
                Cancel
            </button>
            <button type="button" @click.prevent="downloadRecord"
                class="w-24 h-10 font-medium tracking-wide text-white text-sm capitalize transition-colors duration-200 transform bg-indigo-600 hover:bg-indigo-500 focus:outline-none">
                Download
            </button>
        </div>
    </div>
</template>
<script setup>
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
const props = defineProps({
    model: {
        type: Function,
        required: true
    }
})
const router = useRouter();
const model = computed(() => props.model)
const record = ref('')
const recordsToDownload = ref(new Set())

async function downloadRecord() {

    const response = await model.value.store.download([...recordsToDownload.value])
    if (response) {
        const fileName = generateFileName()
        let link = document.createElement('a')
        link.href = window.URL.createObjectURL(response.data)
        link.download = fileName
        link.click()
        link.remove()
    }
    router.push({name:model.value.name+"s"})
}

function generateFileName() {
    const date = new Date(Date.now())
    const day = date.getDate();
    const month = date.getMonth() + 1
    const fullYear = date.getFullYear()
    const formattedDate = day + '_' + (month < 10 ? 0 : '') + month + '_' + fullYear
    return `${model.value.name}s_${formattedDate}.${model.value.fileExtension}`
}
</script>
