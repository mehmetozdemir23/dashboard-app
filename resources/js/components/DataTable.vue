<template>
    <modal v-if="isModalOpen" @close="closeModal">
        <template #modal-content>
            <record-edit-input :record="editingRecord" :editableColumns="editableColumns"
                :columns="props.columns" @close="closeModal" @recordEdited="applyEditRecord" />
        </template>
    </modal>
    <div class="flex flex-col mt-8">
        <div class="py-2 -my-2 overflow-x-scroll sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div
                class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                <table class="min-w-full table-fixed">
                    <thead>
                        <tr>
                            <th v-for="(column, index) in props.columns" :key="column" @click="setSortedColumn(index)"
                                class="w-1/5 md:px-4 px-2 py-3 text-left text-xs font-medium leading-4 tracking-wider  text-gray-500 uppercase border-2 border-gray-200 bg-gray-50">
                                <div class="relative">
                                    <span class="hidden sm:block">{{ column }}</span>
                                    <span class="sm:hidden">{{ column.charAt(0).toUpperCase() }}</span>
                                    <img v-if="isSortedColumn(column)" src="/images/arrow-up-down.svg" alt=""
                                        class="absolute top-1/2 right-0 transform -translate-y-1/2 w-4">
                                </div>
                            </th>
                            <th
                                class="text-center px-2 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-2 border-gray-200 bg-gray-50">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr v-for="(record, index) in props.records" :key="index">
                            <td v-for="(column, index) in props.columns" :key="index"
                                class="px-4 py-4 border-b border-gray-200 whitespace-nowrap">
                                <div class="text-sm font-medium leading-5 text-gray-900">
                                    {{ record[column]?.toString().match(/^[\d]+\.[\d]+$/) ? record[column]?.toFixed(2) :
                                            record[column]
                                    }}
                                </div>
                            </td>
                            <td
                                class="flex justify-center items-center space-x-2 px-4 py-4 border-b border-gray-200 whitespace-nowrap">
                                <button type="button" class="w-10 h-10 flex items-center justify-center bg-red-50"
                                    @click.prevent="deleteRecord(record)">
                                    <img src="/images/minus.svg" alt="" class="w-7">
                                </button>
                                <button type="button" class="w-10 h-10 flex items-center justify-center bg-sky-50"
                                    @click.prevent="editRecord(record)">
                                    <img src="/images/pencil.svg" alt="" class="w-5">
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed, ref } from 'vue';
import Modal from './Modal.vue';
import RecordEditInput from './RecordEditInput.vue';
const emit = defineEmits(['sortedColumnChanged','recordEdited','recordDeleted'])
const props = defineProps({

    columns: {
        type: Array,
        required: true
    },
    editableColumns: {
        type: Array,
    },
    sortedColumn: {
        type: String,
        required: true
    },
    records: {
        type: Array,
        required: true
    }
})

const isModalOpen = ref(false)
const editingRecord = ref(null)

function isSortedColumn(column) {
    return props.sortedColumn == column
}
function setSortedColumn(index) {
    emit('sortedColumnChanged', index)
}
async function deleteRecord(record) {
    emit('recordDeleted',record)
}
async function editRecord(record) {
    isModalOpen.value = true
    editingRecord.value = record
}
async function applyEditRecord(record) {
    emit('recordEdited',record)
}
function closeModal() {
    isModalOpen.value = false
}



</script>
