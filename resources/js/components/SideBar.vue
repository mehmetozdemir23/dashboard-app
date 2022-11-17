<template>
    <div class="flex">
        <!-- Backdrop -->
        <div :class="isSideBarOpen ? 'block' : 'hidden'" @click="homeLayoutStore.toggleSideBar()"
            class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>
        <!-- End Backdrop -->
        <div :class="isSideBarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
            class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-indigo-700 lg:translate-x-0 lg:static lg:inset-0">
            <div class="flex items-center justify-center mt-8">
                <div class="flex items-center">
                    <img src="/images/shn-logo-white.svg" alt="" class="w-36">
                </div>
            </div>

            <nav class="mt-10">
                <side-bar-menu-item label="Dashboard">
                    <template #icon>
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2 10C2 5.58172 5.58172 2 10 2V10H18C18 14.4183 14.4183 18 10 18C5.58172 18 2 14.4183 2 10Z"
                                fill="currentColor" />
                            <path d="M12 2.25195C14.8113 2.97552 17.0245 5.18877 17.748 8.00004H12V2.25195Z"
                                fill="currentColor" />
                        </svg>
                    </template>
                </side-bar-menu-item>
            </nav>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useHomeLayoutStore } from "../stores/homeLayout";
import SideBarMenuItem from "./SideBarMenuItem.vue";
const homeLayoutStore = useHomeLayoutStore()
const isSideBarOpen = computed(() => homeLayoutStore.isSideBarOpen)

</script>
