<template>
    <div class="flex h-screen bg-gray-200 font-roboto">
      <side-bar/>

      <div class="flex-1 flex flex-col overflow-hidden">
        <top-bar />

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
          <div class="container mx-auto px-6 py-8">
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
