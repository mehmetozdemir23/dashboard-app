<template>
    <modal v-if="isModalOpen" @close="closeModal">
        <template #modal-content>
            <component :is="componentInModal" :columns="columns" @close="closeModal" @filterAdded="onFilterAdd"
                @fileDownloaded="onFileDownload" @fileUploaded="onFileUpload" />
        </template>
    </modal>
    <div class="flex flex-col mt-10 space-y-4">
        <div class="flex space-x-2 overflow-x-scroll">
            <button @click.prevent="openModal('filter')" type="button"
                class="flex-shrink-0 w-max flex items-center space-x-2 px-2 sm:px-4 h-10 bg-white">
                <img src="/images/filter.svg" alt="" class="w-6">
                <span class="text-sm">Filter</span>
            </button>
            <button @click.prevent="openModal('download')" type="button"
                class="flex-shrink-0 w-max flex items-center space-x-2 px-2 sm:px-4 h-10 bg-white">
                <img src="/images/folder-arrow-down.svg" alt="" class="w-6">
                <span class="text-sm">Download</span>
            </button>
            <button @click.prevent="openModal('upload')" type="button"
                class="flex-shrink-0 w-max flex items-center space-x-2 px-2 sm:px-4 h-10 bg-white">
                <img src="/images/arrow-up-on-square.svg" alt="" class="w-6">
                <span class="text-sm">Upload</span>
            </button>
        </div>
        <ul class="flex-1 overflow-x-scroll flex space-x-2">
            <li v-for="[column, bounds] of store.getFilters" :key="column">
                <filter-tag :filter="[column, bounds]" @filterDeleted="onFilterDelete(column)" />
            </li>
        </ul>
        <pagination :totalElements="totalRecords" :perPage="perPage" :currentPage="currentPage"
            @page-changed="onPageChange" />
        <data-table :records="records" :columns="columns" :editableColumns="editableColumns"
            :sortedColumn="sortedColumn" @sortedColumnChanged="onSortedColumnChange" @recordEdited="onRecordEdit" @recordDeleted="onRecordDelete" />

    </div>
</template>
<script setup>
import { computed, ref, shallowRef, onMounted } from 'vue';
import DataTable from '../components/DataTable.vue';
import FilterTag from '../components/FilterTag.vue';
import FilterInput from '../components/FilterInput.vue';
import DownloadInput from "../components/DownloadInput.vue";
import UploadInput from "../components/UploadInput.vue"
import Modal from '../components/Modal.vue';
import Pagination from '../components/Pagination.vue';
const props = defineProps({
    model: { type: Function, required: true }
})
const model = props.model
const store = model.store
const records = computed(() => store.getRecords)
const columns = ref(model.columns)
const editableColumns = ref(model.editableColumns)
const sortedColumn = computed(() => store.getSort.column || columns[0])
const componentInModal = shallowRef(null)
const components = {
    'filter': FilterInput,
    'download': DownloadInput,
    'upload': UploadInput
}
const totalRecords = computed(() => store.getTotalRecords)
const perPage = computed(() => store.getPerPage)
const currentPage = ref(1)
const isModalOpen = ref(false)
onMounted(async () => {
    store.fetchRecords()
})
function openModal(componentName) {
    componentInModal.value = components[componentName]
    isModalOpen.value = true
}
function closeModal() {
    isModalOpen.value = false
}
function onSortedColumnChange(index) {
    store.setSort({
        column: columns.value[index],
        order: -store.getSort.order
    })
    store.fetchRecords()
}
function onFilterAdd(filter) {
    store.addFilter(filter)
    store.fetchRecords()
}
function onFilterDelete(column) {
    store.deleteFilter(column)
    store.fetchRecords()
}
async function onRecordEdit(record) {
    await store.edit(record)
    store.fetchRecords()
}
async function onRecordDelete(record){
    await store.delete(record)
    store.fetchRecords()
}
async function onFileUpload(file) {
    await store.upload(file.value)
    store.fetchRecords()
}
async function onFileDownload(file) {
    const fileNameRegex = /^\w+[\w,\s-()[]]*$/
    let fileName = file.name
    if (fileName.match(fileNameRegex)) {
        const response = await store.download(file.name)
        if (response) {
            let link = document.createElement('a')
            link.href = window.URL.createObjectURL(response.data)
            fileName = fileName || generateFileName()
            link.download = `${fileName}.xlsx`
            link.click()
            link.remove()
            closeModal()
        }
    }
}

function onPageChange({ from, to, page }) {
    store.setPaginate({
        from: from,
        to: to
    })
    currentPage.value = page
    store.fetchRecords()
}
</script>
