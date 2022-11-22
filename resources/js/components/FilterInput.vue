<template>
    <div class="flex flex-col space-y-6">
        <ul v-if="filters.size" class="flex w-full overflow-x-scroll pb-4 border-b border-slate-300">
            <li v-for="[column, values] of filters" :key="column">
                <filter-tag :filter="{ column: column, min: values.min, max: values.max }" @filter-deleted="onFilterDelete(column)" />
            </li>
        </ul>
        <button type="button" class="relative w-max flex items-center" @click.prevent="isDropdownOpen = true">
            <div class="space-x-2 flex justify-between items-center">
                <span class="text-lg">
                    {{ filter.column }}
                </span>
                <img src="/images/chevron-down.svg" alt="" class="w-5">
            </div>
            <ul v-if="isDropdownOpen"
                class="absolute -bottom-2 left-0 w-full transform translate-y-full z-50 h-36 drop-shadow-md rounded-md border-2 bg-white p-2 overflow-y-scroll">
                <li v-for="(column, index) in columns" @click.stop="selectColumn(index)"
                    class="w-full text-lg py-2">
                    {{ column }}
                </li>
            </ul>
        </button>
        <div class="flex space-x-2">
            <input v-model="filter.min"
                class="w-full bg-slate-100 pl-10 pr-4 py-4 text-indigo-600 border-gray-200 focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                type="text" placeholder="min" />
            <input v-model="filter.max"
                class="w-full bg-slate-100 pl-10 pr-4 py-4 text-indigo-600 border-gray-200 focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                type="text" placeholder="max" />
        </div>

        <div class="w-full flex justify-end space-x-2 pt-4 bg-white">
            <button type="button" @click.prevent="close"
                class="w-24 h-10 font-medium tracking-wide text-black text-sm capitalize transition-colors duration-200 transform bg-slate-200 hover:bg-slate-200 focus:outline-none">
                Cancel
            </button>
            <button type="button" @click.prevent="addFilter"
                class="w-24 h-10 font-medium tracking-wide text-white text-sm capitalize transition-colors duration-200 transform bg-indigo-600 hover:bg-indigo-500 focus:outline-none">
                Add Filter
            </button>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed } from 'vue';
import FilterTag from './FilterTag.vue';
const props = defineProps({
    model:{
        type:Function,
        required:true
    }

})
const emit = defineEmits(['closed'])

const isDropdownOpen = ref(false)
const model = computed(()=>props.model)
const store = computed(()=>model.value.store)
const columns = computed(()=>model.value.filterableColumns)
const filters = computed(()=>store.value.getFilters)
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
     filter.min = parseFloat(filter.min)
    filter.max = parseFloat(filter.max)
    if (column && filter.min < filter.max) {
        store.value.addFilter(filter)
    }
    close()
}

function onFilterDelete(column){
    store.value.deleteFilter(column)
}
function close() {
    emit('closed')
}
</script>
