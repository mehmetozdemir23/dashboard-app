import axios from "axios";
import { defineStore } from "pinia";
import axiosClient from "../axios";
import router from "../router";

export const useUserStore = defineStore('user', {
    state: () => ({
        data: {},
        token: sessionStorage.getItem('user-token')
    }),
    getters: {
        userInfos: (state) => state.data
    },
    actions: {
        async getUser() {
            if (this.token)
                try {
                    const response = await axiosClient.get('/user')
                    return response.data

                } catch (error) {
                    console.log(error)
                }
        },
        setUser(user) {
            this.data = user;
        },
        setToken(token) {

            this.token = token
            sessionStorage.setItem('user-token', this.token)
        },

        async register(user) {
            console.log(import.meta.env.VITE_APP_URL)
            try {
                const response = await axiosClient.post('/register', user)
                this.setUser(response.data.user)
                this.setToken(response.data.token)
                return { success: response.data }
            } catch (err) {
                const errors = err.response.data.errors
                return { error: errors }
            }
        },

        async login(user) {
            try {
                // await axios.get('/sanctum/csrf-cookie')
                const response = await axiosClient.post('/login', user)
                this.setUser(response.data.user)
                this.setToken(response.data.token)
                return { success: response.data }
            } catch (err) {
                const errors = err.response.data.errors || { credentials: [err.response.data.error] }
                return { error: errors }
            }

        },

        async logout() {
            try {

                this.setUser({})
                this.setToken(null)
                sessionStorage.removeItem('user-token')
                router.push({
                    name: 'login'
                })
            } catch (err) {
                const errors = err.response.data.errors || { credentials: [err.response.data.error] }
                return errors
            }


        },
        async updateUser(userInfos) {
            try {

                const response = await axiosClient.post('/user/update', userInfos)
                return { 'success': 'Password changed successfully' }
            } catch (err) {
                const errors = err.response.data.errors
                return { 'error': errors }
            }
        }
    }

})
