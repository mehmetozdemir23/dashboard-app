<template>
    <home-layout>

        {{ userInfos.name }}
    </home-layout>
</template>
<script setup>
import { ref } from 'vue';
import { useUserStore } from '../stores/user';
import HomeLayout from '../layouts/HomeLayout.vue';
const userStore = useUserStore()
const userInfos = ref(userStore.userInfos)

</script>
