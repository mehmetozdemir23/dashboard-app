<template>
    <home-layout>
        home view
        <form @submit.prevent="logout">
            <button>Logout</button>
        </form>
        <button type="button" @click="getUserInfos">
            get infos
        </button>
    </home-layout>
</template>
<script setup>
import axiosClient from '../axios';
import { useUserStore } from '../stores/user';
import HomeLayout from '../layouts/HomeLayout.vue';

const userStore = useUserStore();

async function getUserInfos(){
    await axiosClient.get('/user');
}
async function logout(){
    await userStore.logout()
}
</script>
