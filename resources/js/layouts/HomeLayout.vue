<template>
    <div class="flex w-full h-screen bg-gray-200 font-roboto">
        <side-bar />

        <div class="flex-1 w-full flex flex-col overflow-hidden">
            <top-bar />
            <main class="flex-1 overflow-hidden bg-gray-200">
                <div class="container w-full p-2 sm:p-6">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import SideBar from "../components/SideBar.vue";
import TopBar from "../components/TopBar.vue";

</script>
