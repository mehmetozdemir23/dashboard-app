<template>
    <div class="w-full">
        <modal v-if="isModalOpen" @close="closeModal">
            <template #modal-content>
                <component :is="componentInModal" :columns="columns" :recordsNumber="selectedRecords.size"
                    :model="model" :fileExtension="fileExtension" :filePrefix="filePrefix" @close="closeModal"
                    @filterAdded="onFilterAdd" @fileDownloaded="onFileDownload" @fileUploaded="onFileUpload"
                    @recordsDeleted="onRecordDelete" />
            </template>
        </modal>
        <div class="flex flex-col space-y-2 mt-4 w-full">
            <div class="flex flex-wrap lg:flex-nowrap justify-between">
                <div class="flex space-x-2">
                    <!-- <button @click.prevent="openModal('new')" type="button"
                        class="flex-shrink-0 min-w-max sm:w-32 w-24 flex justify-center items-center space-x-1 sm:space-x-2 px-2 sm:px-4 h-12 bg-white">
                        <img src="/images/plus.svg" alt="" class="w-4 sm:w-5">
                        <span class="text-sm">New</span>
                    </button> -->
                    <router-link :to="{ name: model.name + '-create' }">
                        <div
                            class="flex-shrink-0 min-w-max sm:w-32 w-24 flex justify-center items-center space-x-1 sm:space-x-2 px-2 sm:px-4 h-12 bg-white">
                            <img src="/images/plus.svg" alt="" class="w-4 sm:w-5">
                            <span class="text-sm">New</span>
                        </div>
                    </router-link>
                    <button @click.prevent="openModal('download')" type="button"
                        class="flex-shrink-0 min-w-max sm:w-32 w-24 flex justify-center items-center space-x-1 sm:space-x-2 px-2 sm:px-4 h-12 bg-white">
                        <img src="/images/folder-arrow-down.svg" alt="" class=" w-5 sm:w-6">
                        <span class="text-sm">Download</span>
                    </button>
                    <button @click.prevent="selectedRecords.size && openModal('remove')" type="button"
                        class="flex-shrink-0 min-w-max sm:w-32 w-24 flex justify-center items-center space-x-1 sm:space-x-2 px-2 sm:px-4 h-12 bg-red-500">
                        <img src="/images/trash.svg" alt="" class="w-5 sm:w-6">
                        <span class="text-sm font-bold text-white">Remove ({{ selectedRecords.size }})</span>
                    </button>
                </div>
                <div class="flex items-center space-x-2 mt-2 md:mt-0">
                    <button @click.prevent="openModal('filter')" type="button"
                        class="flex-shrink-0 w-max flex items-center space-x-2 px-2 sm:px-4 h-12 bg-white">
                        <img src="/images/filter.svg" alt="" class="w-6">
                        <span class="text-sm">Filter</span>
                    </button>
                    <input
                        class="w-48 flex-shrink-0 pl-4 pr-4 h-12 text-indigo-600 outline-none focus:border-b-2 focus:border-indigo-500"
                        type="text" placeholder="Search by number" @input="onSearch" />
                </div>
            </div>
            <ul class="overflow-x-scroll flex space-x-2">
                <li v-for="[column, bounds] of store.getFilters" :key="column">
                    <filter-tag :filter="[column, bounds]" @filterDeleted="onFilterDelete(column)" />
                </li>
            </ul>
            <router-view>

            </router-view>

            <!-- <data-table :records="records" :columns="columns" :editableColumns="editableColumns"
                :sortedColumn="sortedColumn" :selectedRecords="selectedRecords"
                @sortedColumnChanged="onSortedColumnChange" @recordEdited="onRecordEdit"
                @recordSelected="onRecordSelect" @recordUnselected="onRecordUnselect" />
            <pagination :totalElements="totalRecords" :perPage="perPage" :currentPage="currentPage"
                @page-changed="onPageChange" /> -->


        </div>
    </div>
</template>
<script setup>
import { computed, ref, toRef, shallowRef, watch, onMounted } from 'vue';
import DataTable from '../components/DataTable.vue';
import FilterTag from '../components/FilterTag.vue';
import FilterInput from '../components/FilterInput.vue';
import DownloadInput from "../components/DownloadInput.vue";
import NewRecordModal from '../components/NewRecordModal.vue';
import RecordRemoveModal from '../components/RecordRemoveModal.vue';
import Modal from '../components/Modal.vue';
import Pagination from '../components/Pagination.vue';
const props = defineProps({
    model: { type: Function, required: true }
})
const model = toRef(props, 'model')
const store = computed(() => model.value.store)
const records = computed(() => store.value.getRecords)
const columns = computed(() => model.value.columns)
const editableColumns = computed(() => model.value.editableColumns)
const sortedColumn = computed(() => store.value.getSort.column || columns[0])
const fileExtension = computed(() => model.value.fileExtension)
const filePrefix = computed(() => model.value.filePrefix)

const componentInModal = shallowRef(null)
const components = {
    'filter': FilterInput,
    'download': DownloadInput,
    'new': NewRecordModal,
    'remove': RecordRemoveModal
}
const totalRecords = computed(() => store.value.getTotalRecords)
const perPage = computed(() => store.value.getPerPage)
const currentPage = ref(1)
const isModalOpen = ref(false)
const selectedRecords = ref(new Set())
onMounted(() => {
    store.value.fetchRecords()
})
watch(model, () => {
    selectedRecords.value.clear()
    store.value.fetchRecords()
}
)
function openModal(componentName) {
    componentInModal.value = components[componentName]
    isModalOpen.value = true
}
function closeModal() {
    isModalOpen.value = false
}
function onSortedColumnChange(index) {
    store.value.setSort({
        column: columns.value[index],
        order: -store.value.getSort.order
    })
    store.value.fetchRecords()
}
function onFilterAdd(filter) {
    store.value.addFilter(filter)
    store.value.fetchRecords()
}
function onFilterDelete(column) {
    store.value.deleteFilter(column)
    store.value.fetchRecords()
}
async function onRecordEdit(record) {
    await store.value.edit(record)
    store.value.fetchRecords()
}

function onRecordSelect(records) {
    if (records.length)
        records.forEach(record => {
            selectedRecords.value.add(record)
        });

}

function onRecordUnselect(records) {
    if (records.length)
        records.forEach(record => {
            selectedRecords.value.delete(record)
        });

}

async function onRecordDelete() {
    const records = [...selectedRecords.value]
    await store.value.delete(records)
    store.value.fetchRecords()
    selectedRecords.value.clear()
    document.querySelector('#select-all-records').checked = false
    console.log(
        selectedRecords.value.size
    );
}
async function onFileUpload(file) {
    await store.value.upload(file.value)
    store.value.fetchRecords()
}
async function onFileDownload(file) {
    const fileNameRegex = /^\w+[\w,\s-()[]]*$/
    let fileName = file.name
    if (fileName.match(fileNameRegex)) {
        const response = await store.value.download(selectedRecords.value)
        if (response) {
            let link = document.createElement('a')
            link.href = window.URL.createObjectURL(response.data)
            fileName = fileName || generateFileName()
            link.download = `${fileName}.${fileExtension.value}`
            link.click()
            link.remove()
            closeModal()
        }
    }
}

function onPageChange({ from, to, page }) {
    store.value.setPaginate({
        from: from,
        to: to
    })
    currentPage.value = page
    selectedRecords.value.clear()
    store.value.fetchRecords()
}
function onSearch(event) {
    const searchText = event.target.value
    store.value.setSearch(searchText)
    store.value.fetchRecords()
}
</script>
