<template>
    <div class="overflow-x-scroll flex flex-col space-y-4">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="max-w-fit w-12 h-12 border-b border-gray-200 bg-gray-50">
                        <div class="flex justify-center items-center w-12 h-12">
                            <input id="select-all-records" type="checkbox" class="w-4 h-4" @change="selectAllRecords">
                        </div>
                    </th>
                    <th v-for="(column, index) in columns" :key="column" @click="setSortedColumn(index)"
                        class="w-36 sm:w-96 h-12 text-left text-xs font-medium leading-4 tracking-wider  text-gray-500 uppercase border-l border-b border-gray-200 bg-gray-50">
                        <div class="flex justify-between pr-2 sm:block  relative pl-2">
                            <span class="hidden md:block">{{ column }}</span>
                            <span class="md:hidden">{{ column.charAt(0).toUpperCase() }}</span>
                            <img v-if="isSortedColumn(column)" src="/images/arrow-up-down.svg" alt=""
                                class="sm:absolute sm:top-1/2 sm:right-2 sm:transform sm:-translate-y-1/2 w-4">
                        </div>
                    </th>
                    <th
                        class="text-center px-2 h-12 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-l border-gray-200 bg-gray-50">
                        Action</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <tr v-if="records.length" v-for="(record, index) in records" :key="index"
                    class="border-b border-gray-200 h-14">
                    <td class=" h-full flex justify-center items-center border-gray-200 bg-gray-50">
                        <div class="flex justify-center items-center h-full">
                            <input type="checkbox" class="w-4 h-4" @change="selectRecord(record)"
                                :checked="isRecordSelected(record)">
                        </div>
                    </td>

                    <td v-for="(column, index) in columns" :key="index" class="px-4 h-full whitespace-nowrap">
                        <div class="text-sm font-medium leading-5 text-gray-900">
                            {{ record[column]?.toString().match(/^[\d]+\.[\d]+$/) ? record[column]?.toFixed(2) :
                                    record[column]
                            }}
                        </div>
                    </td>
                    <td class="px-4 h-full border-b border-gray-200 whitespace-nowrap">
                        <div class="flex space-x-4">

                            <router-link :to="$route.fullPath+'/'+record.number">
                                <div class="w-max px-4 py-1 text-sm bg-sky-200 text-sky-800">Voir</div>
                            </router-link>
                        </div>
                    </td>
                </tr>
                <tr v-else>
                    <td colspan="100%" class="w-full h-48">
                        <div class="flex items-center justify-center">
                            Aucun résultat
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <pagination :totalElements="totalRecords" :perPage="perPage" :currentPage="currentPage"
            @page-changed="onPageChange" />
    </div>

</template>
<script setup>
import { computed, ref, onMounted, watch } from 'vue';
import Modal from './Modal.vue';
import Pagination from './Pagination.vue';
import RecordEditInput from './RecordEditInput.vue';
const emit = defineEmits(['sortedColumnChanged', 'recordEdited', 'recordSelected', 'recordUnselected'])
const props = defineProps({
    model: {
        type: Function,
        required: true
    },

})
const model = computed(() => props.model)
const store = computed(() => model.value.store)
const records = computed(() => store.value.getRecords)
const columns = computed(() => model.value.columns)
const editableColumns = computed(() => model.value.editableColumns)
const sortedColumn = computed(() => store.value.getSort.column || columns[0])

const currentPage = ref(1)
const perPage = computed(() => store.value.paginate.perPage)
const totalRecords = computed(() => store.value.totalRecords)

const isModalOpen = ref(false)
const editingRecord = ref(null)

onMounted(() => {
    store.value.get()
})
watch(store, () => { store.value.get() })

function isSortedColumn(column) {
    return sortedColumn.value == column
}
function setSortedColumn(index) {
    store.value.setSort({
        column: columns.value[index],
        order: -store.value.getSort.order
    })
}

async function editRecord(record) {
    isModalOpen.value = true
    editingRecord.value = record
}
async function onRecordEdited(record) {
    await store.value.edit(record)
}
function closeModal() {
    isModalOpen.value = false
}
function selectRecord(record) {
    if (store.value.toBeDeleted.has(record)) {
        store.value.removeFromToBeDeleted(record)
        document.querySelector('#select-all-records').checked = false
    }
    else {
        store.value.addToBeDeleted(record)
    }

}

function selectAllRecords() {
    const allRecordsSelected = document.querySelector('#select-all-records').checked
    if (allRecordsSelected) {
        records.value.forEach(record => {
            store.value.addToBeDeleted(record)
        });
    }
    else
        records.value.forEach(record => {
            store.value.removeFromToBeDeleted(record)
        });
}

function isRecordSelected(record) {
    return store.value.toBeDeleted.has(record)
}

function onPageChange({ from, to, page }) {
    store.value.setPaginate({
        from: from,
        to: to
    })
    currentPage.value = page
}

</script>
