<template>
    <div class="bg-white flex flex-col shadow-md">
        <div class="h-72 overflow-y-scroll bg-slate-300">
            <div class="w-full sticky top-0 h-8 flex items-center bg-white pl-4">
                <h1>Show {{ model.name }}</h1>
            </div>
            <div class="flex flex-col h-64 p-4 justify-evenly">
                <div v-for="(column, index) in columns" :key="index"
                    class="flex justify-between items-center space-x-2">
                    <span class="text-sm text-left">{{ column }}</span>
                    <div class="relative">
                        <input v-model="record[column]" :disabled="!(editableColumns.includes(column))"
                            class="pl-2 w-36 h-10 flex items-center justify-center pr-2 bg-white outline-none border-2 border-indigo-200 focus:border-indigo-800">
                        <img v-if="!(editableColumns.includes(column))" src="/images/lock.svg" alt=""
                            class="absolute top-1/2 right-3 z-50 w-4 transform -translate-y-1/2">
                    </div>

                </div>
                <div class="flex justify-between items-center space-x-2">
                    <span class="text-sm text-left">grumes</span>

                    <button type="button" @click.prevent="showGrumes"
                        class="w-36 h-10 flex items-center justify-center pr-2 bg-indigo-600 text-white text-sm">
                        Show
                    </button>


                </div>
            </div>
        </div>
        <div class="w-full flex justify-end space-x-2 p-2 border-t border-slate-200 bg-white">
            <button type="button" @click.prevent="cancel"
                class="w-20 h-10 font-medium tracking-wide text-black text-sm capitalize transition-colors duration-200 transform bg-slate-200 hover:bg-slate-200 focus:outline-none">
                Cancel
            </button>
            <button type="button" @click.prevent="edit"
                class="w-20 h-10 font-medium tracking-wide text-white text-sm capitalize transition-colors duration-200 transform bg-indigo-600 hover:bg-indigo-500 focus:outline-none">
                Edit
            </button>
        </div>
    </div>
</template>
<script setup>
import { computed, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useGrumeStore } from '../stores/grume';
const props = defineProps({
    model: {
        type: Function,
        required: true
    },
    record: {
        type: String,
        required: true
    }
})
const router = useRouter();
const record = ref({})
const model = computed(() => props.model)
const store = computed(() => model.value.store)

const columns = computed(() => model.value.columns)
const editableColumns = computed(() => model.value.editableColumns)

onMounted(async () => {
    record.value = await store.value.show(props.record)
})

function edit() {
    store.value.edit(record.value);
}

function showGrumes() {
    const grumeStore = useGrumeStore()
    grumeStore.addFilter({
        column: 'container_number',
        min: record.value.number,
        max: record.value.number
    })
    router.push({ name: 'grumes' })
}
</script>
