<template>
    <div class="flex flex-col">
        <button type="button" class="relative w-36 text-2xl flex items-center" @click.prevent="isDropdownOpen = true">
            <div class="w-full flex justify-between">
                <span>
                    {{ filter.column }}
                </span>
                <img src="/images/chevron-down.svg" alt="" class="w-7 mt-auto">
            </div>
            <ul v-if="isDropdownOpen"
                class="absolute -bottom-2 left-0 w-full transform translate-y-full z-50 h-36 drop-shadow-md rounded-md border-2 bg-white p-2 overflow-y-scroll">
                <li v-for="(column, index) in columns" @click.stop="selectColumn(index)"
                    class="w-full text-lg py-2">
                    {{ column }}
                </li>
            </ul>
        </button>
        <div class="flex space-x-2 mt-6">
            <input v-model="filter.min"
                class="w-full bg-slate-100 pl-10 pr-4 py-4 text-indigo-600 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                type="text" placeholder="min" />
            <input v-model="filter.max"
                class="w-full bg-slate-100 pl-10 pr-4 py-4 text-indigo-600 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                type="text" placeholder="max" />
        </div>

        <div class="ml-auto mt-20 flex space-x-4">

            <button type="button" @click.prevent="close"
                class="w-24 px-4 py-3 font-medium tracking-wide text-black capitalize transition-colors duration-200 transform bg-slate-300 rounded-md hover:bg-slate-200 focus:outline-none">
                Cancel
            </button>
            <button type="button" @click.prevent="addFilter"
                class="w-max px-4 py-3 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-600 rounded-md hover:bg-indigo-500 focus:outline-none">
                Add Filter
            </button>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive } from 'vue';
const props = defineProps({
    columns:{
        type:Array,
        required:true
    }
})
const emit = defineEmits(['close','filterAdded'])

const isDropdownOpen = ref(false)
const columns = ref([...props.columns])
console.log(columns.value);
const filter = reactive({
    column: columns.value[0],
    min: null,
    max: null
})

function selectColumn(index) {
    isDropdownOpen.value = false
    filter.column = columns.value[index]
}

function addFilter() {
    const column = filter.column
    const min = parseFloat(filter.min)
    const max = parseFloat(filter.max)
    if (column && min < max) {
        emit('filterAdded',filter)
    }
    close()
}
function close() {
    emit('close')
}
</script>
