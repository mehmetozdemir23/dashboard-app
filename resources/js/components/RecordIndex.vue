<template>
    <div>
        <data-table :model="model"/>
    </div>
</template>
<script setup>
import { computed } from 'vue';
import DataTable from './DataTable.vue';
const props = defineProps({
    model:{
        type:Function,
        required:true
    }
})
const model = computed(()=>props.model)
</script>
