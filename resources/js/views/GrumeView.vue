<template>
    <div class="flex space-x-4 mt-10">
        <button @click.prevent="openModal('filter')" type="button" class="w-max flex items-center space-x-2 px-4 h-10 bg-white">
            <img src="/images/filter.svg" alt="" class="w-6">
            <span>Filter</span>
        </button>
        <button @click.prevent="openModal('download')" type="button" class="w-max flex items-center space-x-2 px-4 h-10 bg-white">
            <img src="/images/folder-arrow-down.svg" alt="" class="w-6">
            <span>Download</span>
        </button>
        <button @click.prevent="openModal('upload')" type="button" class="w-max flex items-center space-x-2 px-4 h-10 bg-white">
            <img src="/images/arrow-up-on-square.svg" alt="" class="w-6">
            <span>Upload</span>
        </button>

        <modal v-if="isModalOpen" @close="closeModal" >
            <template #modal-content>
                <component :is="componentInModal" @close="closeModal"  />
            </template>
        </modal>

        <ul class="flex-1 overflow-x-scroll flex space-x-2">
            <li v-for="filter in grumeStore.getFilters" :key="filter.column">
                <filter-tag :filter="filter" />
            </li>
        </ul>
    </div>
    <data-table model="grumes" :records="grumes" :columns="columns" :editableColumns="editableColumns" />
    <pagination :totalElements="totalGrumes" :perPage="perPage" @page-changed="paginate" />
</template>
<script setup>
import { computed, ref,shallowRef, onMounted } from "vue"
import DataTable from '../components/DataTable.vue';
import Modal from '../components/Modal.vue';
import FilterInput from '../components/FilterInput.vue';
import FilterTag from '../components/FilterTag.vue';
import Pagination from '../components/Pagination.vue';
import DownloadInput from "../components/DownloadInput.vue";
import UploadInput from "../components/UploadInput.vue"
import { useGrumeStore } from '../stores/grume';
const grumeStore = useGrumeStore();
const grumes = computed(() => grumeStore.getRecords)
const totalGrumes = computed(() => grumeStore.getTotalRecords)
const perPage = computed(() => grumeStore.getPerPage)
const columns = ref(['number', 'length', 'diameter', 'volume'])
const editableColumns = ref(['length','diameter'])
const isModalOpen = ref(false)
const componentInModal = shallowRef(null)
const components = {
    'filter':FilterInput,
    'download':DownloadInput,
    'upload':UploadInput
}
onMounted(() => {
    grumeStore.fetchRecords()
})
function openModal(componentName){
    componentInModal.value = components[componentName]
    isModalOpen.value = true
}
function closeModal(){
    isModalOpen.value = false
}



</script>
