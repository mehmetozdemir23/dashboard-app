<template>
    <notification alert-type="error" :messages="errors" @closed="onCloseNotification" />
    <div class="bg-white flex flex-col shadow-md">
        <div class="h-72 overflow-y-scroll bg-slate-300">
            <div class="w-full h-8 flex items-center bg-white pl-4">
                <h1>New {{ model.name }}</h1>
            </div>
            <div class="flex flex-col space-y-4 p-4">
                <div v-for="(column, index) in columns" :key="index"
                    class="flex justify-between items-center space-x-2">
                    <span class="text-sm text-left">{{ column }}</span>
                    <input type="text" v-model="record[column]"
                        class="pl-2 w-32 h-10 bg-white outline-none border-2 border-indigo-200 focus:border-indigo-800">
                </div>
            </div>
        </div>
        <div class="w-full flex justify-end space-x-2 p-2 border-t border-slate-200 bg-white">
            <button type="button" @click.prevent="cancel"
                class="w-20 h-10 font-medium tracking-wide text-black text-sm capitalize transition-colors duration-200 transform bg-slate-200 hover:bg-slate-200 focus:outline-none">
                Cancel
            </button>
            <button type="button" @click.prevent="create"
                class="w-20 h-10 font-medium tracking-wide text-white text-sm capitalize transition-colors duration-200 transform bg-indigo-600 hover:bg-indigo-500 focus:outline-none">
                Create
            </button>
        </div>
    </div>
</template>
<script setup>
import { computed, ref, watch } from 'vue';
import {useRouter} from 'vue-router';
import Notification from './Notification.vue';
const props = defineProps({
    model: {
        type: Function,
        required: true
    },
    defaultContainer:{
        type:String,
        default:''
    }

})
const defaultContainer = computed(()=>props.defaultContainer)
const model = computed(() => props.model)
const store = computed(() => model.value.store)
const columns = computed(() => model.value.creatableColumns)
const record = ref(Object.fromEntries(columns.value.map(col => [col, null])))
const errors = ref([])
const router = useRouter()
record.value['container_number'] = defaultContainer.value


function checkErrors() {
    for (const column in record.value) {
        if (record.value[column] == null)
            errors.value.push(column+' is missing')
    }
}

async function create() {
    checkErrors()
    if(!errors.value.length){
        await store.value.create(record.value)

        if(record.value['container_number'])
            router.go(-1)
        else {
            router.push({name:'containers'})
        }
    }
}

function onCloseNotification() {
    errors.value = []
}

function cancel(){
    router.push({name:'grumes'})
}
</script>
