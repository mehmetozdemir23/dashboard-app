import axios from "axios";
import { useUserStore } from "./stores/user";


const axiosClient = axios.create({
    baseURL: `${import.meta.env.VITE_APP_URL}/api`
})

axiosClient.interceptors.request.use(config => {
    const userStore = useUserStore()
    config.headers.Authorization = `Bearer ${userStore.token}`
    return config;
})

axiosClient.interceptors.response.use(response => {
    return response;
}, error => {
    if (error.response.status === 401) {
        sessionStorage.removeItem('user-token')
        // router.push({name: 'Login'})
    } else if (error.response.status === 404) {
        //router.push({name: 'NotFound'})
    }
    throw error;
})
export default axiosClient;
