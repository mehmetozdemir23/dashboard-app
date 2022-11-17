<template>
    <router-link class="router-link relative flex items-center px-6 py-2 mt-4 duration-200 text-white"
        @click="homeLayoutStore.toggleSideBar()"
        :to="{name:props.label}">
        <div class="absolute left-0 left-border h-full"></div>
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
<style scoped>
.router-link:hover .left-border{
    width:6px;
    background:white;
}
.router-link-active{
    background:rgba(0,0,0,0.5);
}
.router-link-active .left-border{
    width:6px;
    background:white;
}
</style>
