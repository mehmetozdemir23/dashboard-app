<template>
    <div
        class="flex-shrink-0 w-max px-2 h-10 flex space-x-4 items-center font-medium tracking-wide text-white transition-colors duration-200 transform bg-indigo-600 hover:bg-indigo-500 focus:outline-none">
        <span>{{ filter.column}} | {{ filter.min }} - {{ filter.max }}</span>
        <button type="button" @click.prevent="deleteFilter">
            <img src="/images/x-mark-white.svg" alt="" class="w-5">
        </button>
    </div>
</template>
<script setup>
import { toRef } from 'vue';
const emit=defineEmits(['filterDeleted'])
const props = defineProps({
    filter: {
        type: Object,
        required: true
    }
})
const filter = toRef(props,'filter')
function deleteFilter(){
    emit('filterDeleted',filter.value)
}
</script>
