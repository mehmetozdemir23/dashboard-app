<template>
    <div
        class="flex-shrink-0 w-max pl-4 pr-2 h-10 flex space-x-4 items-center font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-600 rounded-md hover:bg-indigo-500 focus:outline-none">
        <span>{{ filter[0]}} | {{ filter[1].min }} | {{ filter[1].max }}</span>
        <button type="button" @click.prevent="deleteFilter">

            <img src="/images/close.svg" alt="" class="w-6">
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
console.log(props.filter)
const filter = toRef(props,'filter')
function deleteFilter(){
    emit('filterDeleted')
}
</script>
