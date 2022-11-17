<template>
    <settings-bar :model="model" />
    <data-table :model="model" />
</template>
<script setup>
import { computed } from 'vue';
import SettingsBar from './SettingsBar.vue';
import DataTable from './DataTable.vue';
const props = defineProps({
    model:{
        type:Function,
        required:true
    }
})
const model = computed(()=>props.model)
</script>
