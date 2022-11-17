<template>
    <router-view></router-view>
</template>
<script setup>
import { useContainerStore } from "./stores/container"
import { useGrumeStore } from './stores/grume';
const grumeStore = useGrumeStore()
const containerStore = useContainerStore()
containerStore.init()
grumeStore.init()
</script>

