<template>
    <router-link class="flex items-center px-6 py-2 mt-4 duration-200 border-l-4"
        @click="homeLayoutStore.toggleSideBar()" :class="[$route.name === props.label ? activeClass : inactiveClass]"
        :to="{name:props.label}">
        <slot name="icon" />
        <span class="mx-4">{{ props.label }}</span>
    </router-link>
</template>
<script setup>
import { ref } from 'vue';
import { useHomeLayoutStore } from "../stores/homeLayout"
const homeLayoutStore = useHomeLayoutStore();
const props = defineProps({
    label: {
        type: String,
        required: true
    },
    isActive: {
        type: Boolean,
        default: false
    }
})
const activeClass = ref(
    "bg-gray-600 bg-opacity-25 text-gray-100 border-gray-100"
);
const inactiveClass = ref(
    "border-gray-900 text-gray-500 hover:bg-gray-600 hover:bg-opacity-25 hover:text-gray-100"
);
</script>
