<template>
    <div class="flex items-center justify-center h-screen px-6 bg-gray-200">
        <notification :messages="errorMessages" :alertType="alertType" @closed="errors = {}" />
        <div class="w-full max-w-sm p-6 bg-white rounded-md shadow-md">
            <div class="flex items-center justify-center">
                <img src="/images/personal-logo.svg" alt="" class="w-36">
            </div>

            <form class="mt-8" @submit.prevent="login">
                <label class="block mt-6">
                    <span class="text-md text-gray-700">Email</span>
                    <input type="email"
                        class="text-lg block w-full h-14 pl-2 mt-2 bg-slate-100 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                        v-model="user.email" />
                </label>

                <label class="block mt-6">
                    <span class="text-md text-gray-700">Password</span>
                    <input type="password"
                        class="text-lg block w-full h-14 pl-2 mt-2 bg-slate-100 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                        v-model="user.password" />
                </label>

                <div class="flex items-center justify-between mt-6">
                    <div class="flex-shrink-0 flex items-center">
                        <label class="inline-flex items-center">
                            <input type="checkbox" v-model="user.remember"
                                class="text-indigo-600 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500" />
                            <span class="mx-2 text-sm md:text-md text-gray-600">Remember me</span>
                        </label>
                    </div>

                    <div class="flex-shrink-0 flex items-center">
                        <a class="block text-sm md:text-md text-indigo-700 fontme hover:underline" href="#">Forgot your
                            password?</a>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit"
                        class="w-full px-4 py-2 text-md text-center text-white bg-indigo-600 rounded-md focus:outline-none hover:bg-indigo-500">
                        Sign in
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, computed } from "vue";
import { useUserStore } from "../stores/user";
import { useRouter } from "vue-router";
import Notification from "../components/Notification.vue";

const router = useRouter();
const userStore = useUserStore()
const user = reactive({
    email: "user@example.com",
    password: "password",
    remember: false
})
const alertType = ref('')
const errors = ref({})
const errorMessages = computed(() => {
    const messages = Object.values(errors.value).map(error=>error[0])
    if (messages.length) alertType.value = "error"
    return messages
})
async function login() {
    const response = await userStore.login(user)
    console.log(response)
    if (response.success)
        router.push({ name: 'home' });
    else if (response.error) {
        errors.value = response.error
    }
}
</script>
