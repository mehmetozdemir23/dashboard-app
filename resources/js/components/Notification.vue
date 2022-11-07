<template>

    <div v-if="props.messages.length"
    :class="bgColor"
        class="w-max flex space-x-4 absolute top-10 left-1/2 transform -translate-x-1/2 rounded-md">
        <ul class="flex-shrink-0 px-8 py-4 list-disc">
            <li v-for="message in props.messages" :key="message" class="text-md text-white">
                {{ message }}
            </li>
        </ul>

        <button type="button" class="h-auto flex justify-center items-start pr-2 pt-2" @click.prevent="emit('closed')">

            <img src="/images/close.svg" alt="" class="w-6">
        </button>

    </div>
</template>
<script setup>
import { computed } from 'vue';
const emit = defineEmits([
    'closed'
])
const props = defineProps({
    messages: {
        type: Array,
        required: true
    },
    alertType: {
        type: String,
        required: true
    }
})
const bgColor = computed(() => {
    if (props.alertType == "info")
        return 'bg-sky-500'
    else if (props.alertType == "error") {
        return 'bg-red-500'
    }
    else if (props.alertType == "success") {
        return 'bg-green-500'
    }
})
</script>
