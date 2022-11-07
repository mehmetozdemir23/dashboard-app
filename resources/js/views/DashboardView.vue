<template>
    <home-layout>
        <div class="flex space-x-4">
            <router-link :to="{ name: 'grumes' }">
                <button type="button"
                :class="{'bg-indigo-600 text-white' : $route.name == 'grumes'}"
                    class="px-4 py-2 font-medium tracking-wide text-indigo-600 capitalize transition-colors duration-200 transform bg-white rounded-md hover:text-white hover:bg-indigo-500 focus:outline-none focus:bg-indigo-500">
                    Grumes
                </button>
            </router-link>
            <router-link :to="{ name: 'containers' }">
                <button type="button"
                    :class="{'bg-indigo-600 text-white' : $route.name == 'containers'}"
                    class="px-4 py-2 font-medium tracking-wide text-indigo-600 capitalize transition-colors duration-200 transform bg-white rounded-md hover:text-white hover:bg-indigo-500 focus:outline-none focus:bg-indigo-500">
                    Containers
                </button>
            </router-link>
        </div>
        <router-view></router-view>
    </home-layout>
</template>
<script setup>
import HomeLayout from '../layouts/HomeLayout.vue';
</script>
