<template>
    <router-view></router-view>
</template>
<script setup>
import { useGrumeStore } from './stores/grume';
    const grumeStore = useGrumeStore()
    grumeStore.init()
</script>

