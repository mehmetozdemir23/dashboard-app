<template>
    <notification alertType="error" :messages="errorMessages" @closed="errorMessages = []" />
    <div class="bg-white flex flex-col shadow-md">
        <div class="h-72 overflow-y-scroll bg-slate-300">
            <div class="w-full h-8 flex items-center bg-white pl-4">
                <h1>New {{ model.name }}</h1>
            </div>
            <div v-if="step == 0" class="flex flex-col space-y-4 p-4">
                <div v-for="(column, index) in columns" :key="index"
                    class="flex justify-between items-center space-x-2">
                    <span class="text-sm text-left">{{ column }}</span>
                    <input type="text" v-model="container[column]"
                        class="pl-2 w-32 h-10 bg-white outline-none border-2 border-indigo-200 focus:border-indigo-800">
                </div>
            </div>
            <div v-else class="">
                <div class="flex flex-col overflow-y-scroll ">
                    <div class="flex sticky z-50 top-0 p-4 bg-sky-500">
                        <input type="text" v-model="grume" placeholder="Add grume by number"
                            class="h-auto w-full pl-2 outline-none focus:border-2 border-indigo-600">
                        <button @click.prevent="container.grumes.add(grume)" type="button"
                            class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-indigo-600">
                            <img src="/images/plus-white.svg" alt="" class="w-6">
                        </button>
                    </div>
                    <ul class="grid grid-cols-2 p-4 gap-2 flex-1">
                        <li v-for="(grume, index) in container.grumes" :key="index"
                            class="relative w-full h-10 flex justify-center items-center text-white bg-indigo-600">
                            <span class="">
                                {{ grume }}
                            </span>
                            <button @click.prevent="container.grumes.delete(record)" type="button"
                                class="absolute right-2">
                                <img src="/images/x-mark-white.svg" alt="" class="w-6">
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="w-full flex justify-end space-x-2 p-2 border-t border-slate-200 bg-white">
            <button v-if="step == 0" type="button" @click.prevent="cancel"
                class="w-20 h-10 font-medium tracking-wide text-black text-sm capitalize transition-colors duration-200 transform bg-slate-200 hover:bg-slate-200 focus:outline-none">
                Cancel
            </button>
            <button v-else type="button" @click.prevent="goPrevious"
                class="w-20 h-10 font-medium tracking-wide text-black text-sm capitalize transition-colors duration-200 transform bg-slate-200 hover:bg-slate-200 focus:outline-none">
                Previous
            </button>
            <button v-if="step == 0" type="button" @click.prevent="goNext"
                class="w-20 h-10 font-medium tracking-wide text-white text-sm capitalize transition-colors duration-200 transform bg-indigo-600 hover:bg-indigo-500 focus:outline-none">
                Next
            </button>
            <button v-else type="button" @click.prevent="create"
                class="w-20 h-10 font-medium tracking-wide text-white text-sm capitalize transition-colors duration-200 transform bg-indigo-600 hover:bg-indigo-500 focus:outline-none">
                Create
            </button>
        </div>
    </div>
</template>
<script setup>
import { computed, ref } from 'vue';
import Notification from './Notification.vue';
const props = defineProps({
    model: {
        type: Function,
        required: true
    }
})
const model = computed(() => props.model)
const columns = computed(() => model.value.creatableColumns)
const container = ref(Object.assign(Object.fromEntries(columns.value.map(col => [col, null])), { grumes: new Set() }))
const grume = ref('')
const step = ref(0)
const errorMessages = ref([])
function goNext() {
    for (const column in container.value) {
        if (container.value[column] == null)
            errorMessages.value.push(column + ' is missing')
    }
    !errorMessages.value.length && step.value++
}

function goPrevious() {
    step.value--
}

async function create() {
    if(container.value.grumes.size < 1)
        errorMessages.value.push('grumes are missing')
    else{
        container.value.grumes = [...container.value.grumes]
        await model.value.store.create(container.value)
    }

}
</script>
