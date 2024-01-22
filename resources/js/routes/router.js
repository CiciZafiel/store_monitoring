import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: '/',
        name: 'main',
        component: () => import('../contents/layout/main.vue'),
        redirect: '/dashboard',
        children: [
            {
                path: '/dashboard',
                name: 'dashboard',
                meta: {},
                component: () => import('../contents/pages/dashboard/index.vue'),
            },
            {
                path: '/store',
                name: 'store',
                meta: {},
                component: () => import('../contents/pages/store/index.vue'),
            },
            {
                path: '/sales',
                name: 'sales',
                meta: {},
                component: () => import('../contents/pages/sales/index.vue'),
            },
        ],
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;