<template>
    <notification alertType="error" :messages="errors" @closed="errors = []" />
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
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
import Notification from './Notification.vue';
const props = defineProps({
    model: {
        type: Function,
        required: true
    }
})
const router = useRouter();
const model = computed(() => props.model)
const columns = computed(() => model.value.creatableColumns)
const record = ref(Object.assign(Object.fromEntries(columns.value.map(col => [col, null]))))
const errors = ref([])

function checkErrors() {
    for (const column in record.value) {
        if (record.value[column] == null)
            errors.value.push(column+' is missing')
    }
}

async function create() {
    checkErrors()
    if(!errors.value.length){
        await model.value.store.create(record.value)
        router.push({name:'containers'})
    }

}

function cancel(){
    router.push({name:'containers'})
}
</script>
