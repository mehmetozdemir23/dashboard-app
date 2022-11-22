<template>
    <modal v-if="isModalOpen" @closed="closeModal">
        <template #modal-content>
            <filter-input :model="model" @closed='closeModal' />
        </template>
    </modal>
    <div class="flex flex-col sm:flex-row sm:justify-between space-y-2 sm:space-y-0">
        <div class="flex space-x-2">
            <router-link :to="{ name: model.name + '-create' }">
                <div
                    class="flex-shrink-0 min-w-max w-24 flex justify-center items-center space-x-2 px-2 sm:px-4 h-10 bg-white">
                    <img src="/images/plus.svg" alt="" class="w-4 sm:w-5">
                    <span class="text-sm">New</span>
                </div>
            </router-link>
            <button type="button" @click.prevent="deleteRecord">
                <div
                    class="flex-shrink-0 min-w-max flex justify-center items-center space-x-2 px-2 sm:px-4 h-10 bg-red-500">
                    <img src="/images/trash.svg" alt="" class="w-4 sm:w-5">
                    <span class="text-sm text-white">Delete ({{ toBeDeleted.size }})</span>
                </div>
            </button>
        </div>
        <div class="flex space-x-2">
            <button type="button" @click.prevent="openModal"
                class="flex-shrink-0 min-w-max w-24 flex justify-center items-center space-x-2 px-2 sm:px-4 h-10 bg-white">
                <img src="/images/filter.svg" alt="" class="w-4 sm:w-5">
                <span class="text-sm">Filter</span>
            </button>
            <div class="relative">
                <input type="text" @input="search" :value="searchedNumber"
                    class="w-full sm:w-44 h-10 pl-2 text-sm outline-none focus:border-2 focus:border-indigo-600"
                    placeholder="Search by number">
                    <button @click.prevent="search('')" type="button" class="absolute right-2 top-1/2 -translate-y-1/2">
                        <img v-if="searchedNumber" src="/images/x-mark.svg" alt="" class="w-5"/>
                    </button>
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed, ref } from 'vue';
import Modal from './Modal.vue';
import FilterInput from './FilterInput.vue';
const props = defineProps({
    model: {
        type: Function,
        required: true
    }
})
const isModalOpen = ref(false)
const model = computed(() => props.model)
const store = computed(() => model.value.store)
const toBeDeleted = computed(() => store.value.toBeDeleted)
const searchedNumber = computed(() => store.value.getSearch.text);

function search(event) {
    const number = event.target?.value ?? ''
    store.value.setSearch(number)
}


function deleteRecord() {
    store.value.delete()
}

function openModal() {
    isModalOpen.value = true
}
function closeModal() {
    isModalOpen.value = false
}
</script>
