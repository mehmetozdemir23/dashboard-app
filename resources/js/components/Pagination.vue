<template>
    <div class="flex">
        <button type="button" :disabled="isFirstPage"
            :class="{ 'cursor-not-allowed hover:bg-white text-slate-300': isFirstPage }"
            class="px-3 h-10 ml-0 leading-tight text-indigo-700 bg-white border-r border-gray-200 hover:bg-indigo-600 hover:text-white"
            @click.prevent="changePage(1)">
            <span class="hidden sm:block">First</span>
            <img src="/images/chevron-double-left.svg" alt="" class="w-4 sm:hidden">
        </button>
        <button type="button" :disabled="isFirstPage"
            :class="{ 'cursor-not-allowed hover:bg-white text-slate-300': isFirstPage }"
            class="px-3 h-10 leading-tight text-indigo-700 bg-white border-r border-gray-200 hover:bg-indigo-600 hover:text-white"
            @click.prevent="changePage(currentPage - 1)">
            <span class="hidden sm:block">Previous</span>
            <img src="/images/chevron-left.svg" alt="" class="w-4 sm:hidden">
        </button>
        <button type="button" v-for="index in pageRange" :key="index" @click.prevent="changePage(index)"
            class="w-10 h-10 leading-tight border-r border-gray-200 hover:bg-indigo-600 hover:text-white"
            :class="currentPageStyle(index)">
            <span>{{ index }}</span>
        </button>
        <button type="button" :disabled="isLastPage"
            :class="{ 'cursor-not-allowed hover:bg-white text-slate-300': isLastPage }"
            class="px-3 h-10 leading-tight text-indigo-700 bg-white border-r border-gray-200 hover:bg-indigo-600 hover:text-white"
            @click.prevent="changePage(currentPage + 1)">
            <span class="hidden sm:block">Next</span>
            <img src="/images/chevron-right.svg" alt="" class="w-4 sm:hidden">
        </button>
        <button type="button" :disabled="isLastPage"
            :class="{ 'cursor-not-allowed hover:bg-white text-slate-300': isLastPage }"
            class="px-3 h-10 leading-tight text-indigo-700 bg-white rounded-r hover:bg-indigo-600 hover:text-white"
            @click.prevent="changePage(pageCount)">
            <span class="hidden sm:block">Last</span>
            <img src="/images/chevron-double-right.svg" alt="" class="w-4 sm:hidden">
        </button>
    </div>
</template>
<script setup>
import { computed, ref, toRef } from 'vue';
const emit = defineEmits(['page-changed'])
const props = defineProps({
    totalElements: {
        type: Number,
        required: true
    },
    perPage: {
        type: Number,
        required: true
    },
    currentPage: {
        type: Number,
        default: 1
    }
})

const currentPage = toRef(props, 'currentPage')
const pageCount = computed(() => Math.ceil(props.totalElements / props.perPage))
const beforeAndAfterCurrentPage = ref(2)
const rangeStart = computed(() => currentPage.value - beforeAndAfterCurrentPage.value)
const rangeEnd = computed(() => 2 * beforeAndAfterCurrentPage.value + rangeStart.value)

const pageRange = computed(() => {
    let start = rangeStart.value
    let end = rangeEnd.value
    if (start < 1) {
        end += 1 - start
        start = 1
    } else if (end > pageCount.value) {
        start -= end - pageCount.value
        end = pageCount.value
    }
    start = Math.max(1, start)
    end = Math.min(end, pageCount.value)
    const rangeSize = end - start + 1
    return Array(rangeSize).fill().map((x, i) => i + start)

})
const isFirstPage = computed(() => currentPage.value === 1)
const isLastPage = computed(() => currentPage.value === pageCount.value)



function changePage(page) {
    const paginatedFrom = (page - 1) * props.perPage
    const paginatedTo = Math.min(paginatedFrom + props.perPage - 1, props.totalElements)

    emit('page-changed', {
        page: page,
        from: paginatedFrom,
        to: paginatedTo
    })
}

function currentPageStyle(page) {
    if (currentPage.value === page) {
        return 'bg-indigo-600 text-white'
    }
    return 'text-indigo-700 bg-white'
}

</script>
