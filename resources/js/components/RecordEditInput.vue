<template>
    <div class="flex flex-col">
        <h1 class="text-2xl mr-auto">
            Edit Record
        </h1>
        <div class="flex justify-between mt-4 w-full">
            <div v-for="column in columns" :key="column">
                <label :for="column" class="w-max flex flex-col space-y-2">
                    <p class="text-lg text-slate-600">{{ column.charAt(0).toUpperCase() + column.slice(1) }}</p>
                    <div class="w-24 flex rounded-lg bg-slate-100 p-2">
                        <input type="text" :readonly="!editableColumns.includes(column)"
                        :value="record[column]?.toString().match(/^[\d]+\.[\d]+$/) ? record[column]?.toFixed(2) : record[column]"
                        class="w-full text-2xl bg-transparent outline-none"
                        @input="event => record[column] = event.target.value"
                        >
                        <img v-if="!editableColumns.includes(column)" src="/images/lock.svg" alt="" class="w-4">
                    </div>
                </label>
            </div>
        </div>
        <div class="ml-auto mt-20 flex space-x-4">
            <button type="button" @click.prevent="close"
                class="w-24 px-4 py-3 font-medium tracking-wide text-black capitalize transition-colors duration-200 transform bg-slate-300 rounded-md hover:bg-slate-200 focus:outline-none">
                Cancel
            </button>
            <button type="button" @click.prevent="edit"
                class="w-max px-4 py-3 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-600 rounded-md hover:bg-indigo-500 focus:outline-none">
                Apply
            </button>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive } from 'vue';
const emit = defineEmits(['close','recordEdited'])
const props = defineProps({
    record: {
        type: Object,
        required: true
    },
    columns: {
        type: Array,
        required: true
    },
    editableColumns: {
        type: Array,
        required: true
    },
})
const record = reactive({...props.record})
const columns = ref(props.columns)
const editableColumns = ref(props.editableColumns)

function edit(){
    Object.entries(props.record).forEach(entry=>{
        const [property,value] = entry
        if(typeof(value) == 'number'){
            record[property] = parseFloat(record[property])
        }
    })
    emit('recordEdited',record)
    close()
}
function close() {
    emit('close')
}
</script>
