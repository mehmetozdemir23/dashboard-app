import { createRouter, createWebHistory } from "vue-router";
import LoginView from "../views/LoginView.vue"
import RegisterView from "../views/RegisterView.vue"
import DashboardView from "../views/DashboardView.vue"
import ProfileView from "../views/ProfileView.vue"

import RecordLayout from "../components/RecordLayout.vue"
import RecordIndex from "../components/RecordIndex.vue"
import RecordDownload from "../components/RecordDownload.vue"

import { Grume } from "../models/Grume";
import { Container } from "../models/Container";
import GrumeCreate from "../components/GrumeCreate.vue"
import GrumeShow from "../components/GrumeShow.vue"
import ContainerShow from "../components/ContainerShow.vue"
import ContainerCreate from "../components/ContainerCreate.vue"

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
                redirect: { name: 'grumes' },
                children: [
                    {
                        name: 'grumes',
                        path: 'grumes',
                        redirect: { name: 'grume-index' },
                        props: {
                            model: Grume
                        },
                        component: RecordLayout,
                        children: [
                            {
                                name: 'grume-index',
                                path: '',
                                component: RecordIndex,
                            },
                            {
                                name: 'grume-create',
                                path: 'create',
                                component: GrumeCreate,
                            },
                            {
                                name: 'grume-show',
                                path: '/home/dashboard/grumes/:number',
                                component: GrumeShow,
                                props: route => ({
                                    model: Grume,
                                    record: route.params.number
                                })

                            },
                            {
                                name: 'grume-download',
                                path: 'download',
                                component: RecordDownload,
                            },
                        ]

                    }, {
                        name: 'containers',
                        path: 'containers',
                        props: {
                            model: Container
                        },
                        redirect: { name: 'container-index' },
                        component: RecordLayout,
                        children: [
                            {
                                name: 'container-index',
                                path: '',
                                component: RecordIndex,
                                props: {
                                    model: Container
                                }
                            },
                            {
                                name: 'container-create',
                                path: 'create',
                                component: ContainerCreate,
                                props: {
                                    model: Container
                                }
                            },
                            {
                                name: 'container-show',
                                path: '/home/dashboard/containers/:number',
                                component: ContainerShow,
                                props: route => ({
                                    model: Container,
                                    record: route.params.number
                                })
                            },
                            {
                                name: 'container-download',
                                path: 'download',
                                component: RecordDownload,
                            },
                        ]
                    }
                ]
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
