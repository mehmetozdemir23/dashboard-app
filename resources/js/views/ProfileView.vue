<template>
    <home-layout>
        <notification :messages="successMessage || errorMessages" :alertType="alertType" @closed="onNotificationClose" />
        <div class="mt-4">
            <div class="p-6 bg-white rounded-md shadow-md">
                <h2 class="text-lg font-semibold text-gray-700 capitalize">
                    Account settings
                </h2>

                <form @submit.prevent="register">
                    <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
                        <label class="block">
                            <span class="text-md text-gray-700">Username</span>
                            <input type="text"
                                class="text-lg block w-full h-14 pl-2 mt-2 bg-slate-100 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                                v-model="user.name" />
                        </label>

                        <label class="block">
                            <span class="text-md text-gray-700">Email</span>
                            <input type="text"
                                class="text-lg block w-full h-14 pl-2 mt-2 bg-slate-100 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                                v-model="user.email" />
                        </label>

                        <label class="block">
                            <span class="text-md text-gray-700">New Password</span>
                            <input type="password"
                                class="text-lg block w-full h-14 pl-2 mt-2 bg-slate-100 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                                v-model="user.password" />
                        </label>

                        <label class="block">
                            <span class="text-md text-gray-700">Confirm Password</span>
                            <input type="password"
                                class="text-lg block w-full h-14 pl-2 mt-2 bg-slate-100 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                                v-model="user.password_confirmation" />
                        </label>
                    </div>

                    <div class="flex justify-end space-x-4 mt-12">
                        <button type="button" @click.prevent="reset"
                            class="w-24 px-4 py-3 font-medium tracking-wide text-black capitalize transition-colors duration-200 transform bg-slate-300 rounded-md hover:bg-slate-200 focus:outline-none">
                            Cancel
                        </button>
                        <button type="button" @click.prevent="save"
                            class="w-max px-4 py-3 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-600 rounded-md hover:bg-indigo-500 focus:outline-none">
                            Apply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </home-layout>
</template>
<script setup>
import { reactive, ref, computed, onMounted } from 'vue';
import { useUserStore } from '../stores/user';
import HomeLayout from '../layouts/HomeLayout.vue';
import Notification from '../components/Notification.vue';

const userStore = useUserStore()
const user = reactive({
    name: null,
    email: null,
    password: null,
    password_confirmation: null
})
const alertType = ref('')
const errors = ref(null)
const errorMessages = computed(() => {
    let messages = []
    for (const error in errors.value) {
        messages.push(errors.value[error][0])
    }
    if (messages.length) alertType.value = "error"
    return messages
})
const success = ref(null)
const successMessage = computed(() => {
    if (success.value) alertType.value = "success"
    return success.value && [success.value]
})
onMounted(async () => {
    const infos = await userStore.getUser()
    user.name = infos.name
    user.email = infos.email
})

function onNotificationClose(){
    errors.value = null
    success.value = null
}
function reset() {
    user.password = null;
    user.password_confirmation = null;
}
async function save() {
    const response = await userStore.updateUser(user)
    errors.value = response.error
    success.value = response.success
}
</script>
