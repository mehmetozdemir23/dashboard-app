<template>
    <notification alert-type="error" :messages="errors" @closed="onCloseNotification" />
    <div class="flex flex-col">
        <h1 class="text-xl mr-auto">
            Edit Record
        </h1>
        <div class="flex flex-col  mt-4 space-y-4">
            <div v-for="column in columns" :key="column">
                <label :for="column" class="flex justify-between items-center">
                    <p class="w-48 text-left  text-slate-600">{{ column }}</p>
                    <div class="w-36 flex bg-slate-200 p-2">
                        <input type="text" :readonly="!editableColumns.includes(column)"
                            :value="record[column]?.toString().match(/^[\d]+\.[\d]+$/) ? record[column]?.toFixed(2) : record[column]"
                            class="w-full text-lg bg-transparent outline-none"
                            @input="event => record[column] = event.target.value">
                        <img v-if="!editableColumns.includes(column)" src="/images/lock.svg" alt="" class="w-4">
                    </div>
                </label>
            </div>
        </div>
        <div class="ml-auto mt-20 flex space-x-4">
            <button type="button" @click.prevent="close"
                class="w-20 h-10 font-medium tracking-wide text-black text-sm capitalize transition-colors duration-200 transform bg-slate-200 hover:bg-slate-200 focus:outline-none">
                Cancel
            </button>
            <button type="button" @click.prevent="edit"
                class="w-20 h-10 font-medium tracking-wide text-white text-sm capitalize transition-colors duration-200 transform bg-indigo-600 hover:bg-indigo-500 focus:outline-none">
                Apply
            </button>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive } from 'vue';
import Notification from './Notification.vue';
const emit = defineEmits(['close', 'recordEdited'])
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
const record = reactive({ ...props.record })
const columns = ref(props.columns)
const editableColumns = ref(props.editableColumns)
const errors = ref([])
function edit() {
    checkErrors()
    console.log(errors.value)
    if(!errors.value.length){

        Object.entries(props.record).forEach(entry => {
            const [property, value] = entry
            if (typeof (value) == 'number') {
                record[property] = parseFloat(record[property])
            }
        })
        emit('recordEdited', record)
        close()
    }


}

function checkErrors() {
    for (const column in record) {
        console.log(record[column]==null)
        if (record[column] == '')
            errors.value.push(column + ' is missing')
    }
}
function onCloseNotification(){
    errors.value = []
}
function close() {
    emit('close')
}
</script>
