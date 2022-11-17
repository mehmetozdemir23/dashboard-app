<template>
    <upload-input @file-uploaded="onFileUpload" />
</template>
<script setup>
import UploadInput from './UploadInput.vue';
const props = defineProps({
    model:{
        type:Function,
        required:true
    }
})

async function onFileUpload(file) {
    const store = props.model.store
    await store.upload(file)
    store.fetchRecords()
}
</script>
