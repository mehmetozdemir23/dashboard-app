<template>
    <home-layout>
        <div class="flex">
            <router-link :to="{ name: 'grumes' }">
                <button type="button"
                    class="w-32 h-10 flex justify-center items-center font-medium tracking-wide capitalize transition-colors duration-200 transform bg-white hover:text-white hover:bg-indigo-500 focus:outline-none focus:bg-indigo-500">
                    Grumes
                </button>
            </router-link>
            <router-link :to="{ name: 'containers' }">
                <button type="button"
                    class="w-32 h-10 flex justify-center items-center font-medium tracking-wide capitalize transition-colors duration-200 transform bg-white hover:text-white hover:bg-indigo-500 focus:outline-none focus:bg-indigo-500">
                    Containers
                </button>
            </router-link>
        </div>
        <router-view>
        </router-view>
    </home-layout>
</template>
<script setup>
import HomeLayout from '../layouts/HomeLayout.vue';
</script>
<style scoped>
.router-link-active>button {
    background: #4f46e5;
    color: white;
}
</style>
