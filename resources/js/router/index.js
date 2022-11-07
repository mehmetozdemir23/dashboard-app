import { Grume } from "../models/Grume";
import { createRouter, createWebHistory } from "vue-router";
import LoginView from "../views/LoginView.vue"
import RegisterView from "../views/RegisterView.vue"
import ProfileView from "../views/ProfileView.vue"
import TestView from "../views/TestView.vue"
//import GrumeView from "../views/GrumeView.vue"
import ContainerView from "../views/ContainerView.vue"
import DataView from "../views/DataView.vue"

import DashboardView from "../views/DashboardView.vue"
import { useUserStore } from "../stores/user";
const routes = [
    {
        name: 'root',
        path: '/',
        redirect: '/home',
    },

    {
        name: 'home',
        path: '/home',
        redirect: '/home/dashboard',
        meta: {
            requiresAuth: true
        },
        children: [
            {
                name: 'Dashboard',
                path: 'dashboard',
                component: DashboardView,
                children: [
                    {
                        name: 'grumes',
                        path: 'grumes',
                        component: DataView,
                        props: {
                            model: Grume
                        }

                    }, {
                        name: 'containers',
                        path: 'containers',
                        component: ContainerView
                    }
                ]
            }, {
                name: 'Test',
                path: 'test',
                component: TestView
            }, {
                name: 'Profile',
                path: 'profile',
                component: ProfileView
            }
        ]
    },
    {
        name: 'login',
        path: '/login',
        meta: {
            isGuest: true
        },
        component: LoginView,
    }, {
        name: 'register',
        path: '/register',
        meta: {
            isGuest: true
        },
        component: RegisterView,
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const userStore = useUserStore()
    const token = userStore.token
    if (to.meta.requiresAuth && !token) {
        next({ name: "login" });
    } else if (token && to.meta.isGuest) {
        next({ name: "home" });
    } else {
        next();
    }
});

export default router
