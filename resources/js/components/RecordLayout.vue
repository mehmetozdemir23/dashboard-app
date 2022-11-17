<template>
    <div class="flex flex-col space-y-4 mt-4 overflow-y-scroll">
        <settings-bar :model="model"/>
        <router-view :model="model"></router-view>
    </div>
</template>
<script setup>
import { computed } from 'vue';
import SettingsBar from './SettingsBar.vue';
const props = defineProps({
    model:{
        type:Function,
        required:true
    }
})
const model = computed(()=>props.model)
</script>
