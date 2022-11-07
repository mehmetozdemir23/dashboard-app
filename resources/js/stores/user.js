import { defineStore } from "pinia";
import axiosClient from "../axios";
import router from "../router";

export const useUserStore = defineStore('user', {
    state: () => ({
        data: {},
        token: sessionStorage.getItem('user-token')
    }),
    getters:{
        userInfos:(state) => state.data
    },
    actions: {
        setUser(user) {
            this.data = user;
        },
        setToken(token) {

            this.token = token
            sessionStorage.setItem('user-token', this.token)
        },

        async register(user) {
            try {

                const response = await axiosClient.post('/register', user)
                this.setUser(response.data.user)
                this.setToken(response.data.token)
                return response.data
            } catch (err) {
                const errors = err.response.data.errors
                return errors
            }
        },

        async login(user) {
            try {

                const response = await axiosClient.post('/login', user)
                this.setUser(response.data.user)
                this.setToken(response.data.token)
                return response.data
            } catch (err) {
                const errors = err.response.data.errors || { credentials: [err.response.data.error] }
                return errors
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


        }
    }

})
